<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Drafts blog posts with OpenAI. Uses tech-news headlines only as topic ideas and
 * EG Tech's own catalog as the unique data, so posts are original and link to products.
 * Every post is saved as a draft for an admin to check before publishing.
 */
class BlogWriterService
{
    /** Tech news feeds used for topic ideas (headline + short summary only). */
    public const FEEDS = [
        'GSMArena'       => 'https://www.gsmarena.com/rss-news-reviews.php3',
        'The Verge'      => 'https://www.theverge.com/rss/tech/index.xml',
        'Gadgets 360'    => 'https://feeds.feedburner.com/gadgets360-latest',
        "Tom's Hardware" => 'https://www.tomshardware.com/feeds/all',
        'Notebookcheck'  => 'https://www.notebookcheck.net/News.152.100.html',
    ];

    private const ALLOWED_TAGS = ['h2', 'h3', 'p', 'ul', 'ol', 'li', 'strong', 'em', 'a', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'blockquote', 'br'];

    /**
     * @param string|null $topic  Admin's own topic (e.g. "Best laptops under Rs 1 lakh"). Empty = pick from latest news.
     */
    public function generate(?string $topic = null): Post
    {
        $topic = trim((string) $topic);
        $headlines = $topic === '' ? $this->latestHeadlines() : [];
        if ($topic === '' && !$headlines) {
            throw new RuntimeException('Could not read any news feeds right now. Try again later or type your own topic.');
        }

        $catalog = $this->catalog();
        $data = $this->askModel($this->buildPrompt($topic, $headlines, $catalog));

        $title = trim((string) ($data['title'] ?? ''));
        $body = (string) ($data['body_html'] ?? '');
        if ($title === '' || str_word_count(strip_tags($body)) < 250) {
            throw new RuntimeException('The AI returned an incomplete article. Please try again.');
        }

        $catalogIds = $catalog->pluck('id')->all();
        $relatedIds = array_values(array_intersect(array_map('intval', (array) ($data['related_product_ids'] ?? [])), $catalogIds));

        $body = $this->linkProducts($this->sanitize($body), $catalog);

        // Only keep sources that really came from the feeds we gave it
        $known = collect($headlines)->keyBy('url');
        $sources = collect((array) ($data['source_urls'] ?? []))
            ->filter(fn ($u) => is_string($u) && $known->has($u))
            ->map(fn ($u) => ['title' => $known[$u]['title'], 'url' => $u, 'site' => $known[$u]['site']])
            ->values()->all();

        return Post::create([
            'title'               => Str::limit($title, 180, ''),
            'slug'                => Post::uniqueSlug($data['slug'] ?? $title),
            'excerpt'             => Str::limit(trim((string) ($data['excerpt'] ?? '')), 300),
            'body'                => $body,
            'meta_title'          => Str::limit(trim((string) ($data['meta_title'] ?? $title)), 60, ''),
            'meta_description'    => Str::limit(trim((string) ($data['meta_description'] ?? '')), 160, ''),
            'meta_keywords'       => Str::limit(trim((string) ($data['meta_keywords'] ?? '')), 500, ''),
            'sources'             => $sources,
            'related_product_ids' => $relatedIds,
            'status'              => 'draft',
            'ai_generated'        => true,
        ]);
    }

    /** Latest headlines from all feeds, newest first. Cached for an hour. */
    public function latestHeadlines(int $limit = 30): array
    {
        return Cache::remember('blog.headlines', now()->addHour(), function () use ($limit) {
            $items = [];
            foreach (self::FEEDS as $site => $url) {
                try {
                    $xml = Http::timeout(15)
                        ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; EGTechBlogBot/1.0)'])
                        ->get($url)->throw()->body();
                    $feed = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
                    if (!$feed) continue;

                    $entries = isset($feed->channel->item) ? $feed->channel->item : ($feed->entry ?? []);
                    $n = 0;
                    foreach ($entries as $e) {
                        if (++$n > 6) break; // keep the mix balanced across sites
                        $link = (string) ($e->link['href'] ?? '') ?: trim((string) $e->link);
                        $summary = (string) ($e->description ?? $e->summary ?? '');
                        $items[] = [
                            'site'    => $site,
                            'title'   => trim(html_entity_decode(strip_tags((string) $e->title))),
                            'url'     => $link,
                            'summary' => Str::limit(trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($summary)))), 220),
                            'date'    => strtotime((string) ($e->pubDate ?? $e->published ?? $e->updated ?? '')) ?: 0,
                        ];
                    }
                } catch (\Throwable $ex) {
                    report($ex);
                }
            }

            return collect($items)
                ->filter(fn ($i) => $i['title'] !== '' && Str::startsWith($i['url'], 'http'))
                ->unique('url')->sortByDesc('date')->take($limit)->values()->all();
        });
    }

    /** Active products the article may recommend. */
    private function catalog()
    {
        return Product::with(['brand:id,name', 'category:id,name'])
            ->where('is_active', true)->listed()->latest()->take(80)->get();
    }

    private function buildPrompt(string $topic, array $headlines, $catalog): array
    {
        $products = $catalog->map(fn ($p) => sprintf(
            '#%d | %s | %s | %s | Rs %s | %s',
            $p->id, $p->name, optional($p->brand)->name ?? '-', optional($p->category)->name ?? '-',
            number_format((float) $p->final_price), $p->stock_status === 'in_stock' ? 'in stock' : 'out of stock'
        ))->implode("\n");

        $recent = Post::latest()->take(30)->pluck('title')->implode("\n- ");

        $task = $topic !== ''
            ? "Write an article on this topic chosen by the shop owner: \"{$topic}\"."
            : "Pick ONE headline below that matters most to computer and gadget buyers in Nepal (laptops, PCs, phones, printers, accessories, components) and write an article about it.\n"
              . "Do not copy or closely paraphrase the source. Explain what it means for buyers in Nepal, and connect it to products in our catalog where it fits.\n\n"
              . "Headlines (site | title | url | summary):\n"
              . collect($headlines)->map(fn ($h) => "{$h['site']} | {$h['title']} | {$h['url']} | {$h['summary']}")->implode("\n");

        $system = "You are the content writer for EG Tech Solution, a computer and electronics shop in Dhangadhi, Kailali, Nepal. "
            . "You write original, genuinely helpful articles in clear, simple English for Nepali buyers. Return ONLY valid JSON.";

        $user = $task . "\n\n"
            . "Our catalog (id | name | brand | category | price | stock):\n" . ($products ?: '(empty)') . "\n\n"
            . ($recent ? "Already published or drafted (do not repeat these topics):\n- {$recent}\n\n" : '')
            . "Rules:\n"
            . "- 700 to 1200 words. Useful, specific and honest. No filler, no hype, no clickbait.\n"
            . "- Only state facts found in the headline summaries or the catalog. Never invent specs, launch dates or Nepal prices.\n"
            . "  If a Nepal price or availability is unknown, say to contact EG Tech for the latest price.\n"
            . "- Only quote prices exactly as shown in our catalog.\n"
            . "- When you mention a product from our catalog, write it as [[product:ID]] (e.g. [[product:12]]); it becomes a link.\n"
            . "- body_html uses only <h2>, <h3>, <p>, <ul>, <ol>, <li>, <strong>, <em>, <table>, <tr>, <th>, <td>, <blockquote>. No <h1>, no images, no inline styles.\n"
            . "- End with a short section inviting readers to visit or call EG Tech Solution in Dhangadhi (+977 986-4830276).\n"
            . "- related_product_ids: up to 4 catalog ids that genuinely fit the article (empty array if none fit).\n"
            . "- source_urls: the headline url(s) you used (empty array if writing on the owner's topic).\n\n"
            . "Return JSON with keys: title, slug, excerpt (1-2 sentences), meta_title (<= 60 chars), "
            . "meta_description (140-160 chars), meta_keywords (6-10, comma separated, include Nepal / Dhangadhi where natural), "
            . "body_html, related_product_ids, source_urls.";

        return [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $user],
        ];
    }

    private function askModel(array $messages): array
    {
        $key = config('services.openai.key');
        if (!$key) {
            throw new RuntimeException('OPENAI_API_KEY is not set in .env.');
        }

        $url = rtrim(config('services.openai.base_url', 'https://api.openai.com'), '/') . '/v1/chat/completions';

        $resp = Http::withToken($key)->timeout(150)->retry(2, 1500, throw: false)->acceptJson()->asJson()->post($url, [
            'model'           => config('services.openai.blog_model', 'gpt-4o-mini'),
            'temperature'     => 0.6,
            'response_format' => ['type' => 'json_object'],
            'messages'        => $messages,
        ]);

        if ($resp->failed()) {
            report(new RuntimeException('OpenAI blog request failed: ' . $resp->status() . ' ' . Str::limit($resp->body(), 500)));
            $code = (string) data_get($resp->json(), 'error.code', '') . ' ' . (string) data_get($resp->json(), 'error.type', '');
            throw new RuntimeException(match (true) {
                str_contains($code, 'insufficient_quota') || str_contains($code, 'credit_balance') =>
                    'Your OpenAI account has no credits left. Add credits at platform.openai.com → Settings → Billing, then try again.',
                $resp->status() === 401 => 'The OpenAI API key is invalid. Check OPENAI_API_KEY in .env.',
                $resp->status() === 429 => 'OpenAI is rate-limiting requests. Wait a minute and try again.',
                default => 'The AI service did not respond (HTTP ' . $resp->status() . '). Please try again.',
            });
        }

        $data = json_decode((string) data_get($resp->json(), 'choices.0.message.content', ''), true);
        if (!is_array($data)) {
            throw new RuntimeException('The AI returned an unreadable answer. Please try again.');
        }
        return $data;
    }

    /** Keep only safe tags; drop every attribute except a safe href on links. */
    public function sanitize(string $html): string
    {
        $html = preg_replace('#<(script|style|iframe|object|embed|noscript)\b[^>]*>.*?</\1\s*>#is', '', $html);
        $html = strip_tags($html, self::ALLOWED_TAGS);
        if (trim($html) === '') return '';

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);
        foreach ($xpath->query('//*[@id="root"]//*') as $el) {
            $href = $el->nodeName === 'a' ? $el->getAttribute('href') : null;
            foreach (iterator_to_array($el->attributes) as $attr) {
                $el->removeAttribute($attr->nodeName);
            }
            if ($href !== null && preg_match('#^(https?://|/)#i', $href)) {
                $el->setAttribute('href', $href);
                if (Str::startsWith($href, 'http') && !Str::contains($href, parse_url(config('app.url'), PHP_URL_HOST) ?: '#none#')) {
                    $el->setAttribute('rel', 'nofollow noopener');
                    $el->setAttribute('target', '_blank');
                }
            }
        }

        $root = $doc->getElementById('root');
        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }
        return trim($out);
    }

    /** Turn [[product:ID]] markers into links to our product pages. */
    public function linkProducts(string $html, $catalog): string
    {
        $byId = $catalog->keyBy('id');
        return preg_replace_callback('/\[\[product:(\d+)\]\]/', function ($m) use ($byId) {
            $p = $byId->get((int) $m[1]);
            if (!$p) return '';
            return '<a href="' . e(url('/products/' . $p->slug . '/' . $p->id)) . '">' . e($p->name) . '</a>';
        }, $html);
    }
}
