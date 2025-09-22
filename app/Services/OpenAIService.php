<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OpenAIService
{
    /**
     * Generate SEO data for a product (Nepal-focused).
     * Signature unchanged for compatibility.
     */
    public function generateSeoData(string $name, string $category = 'General', string $features = ''): array
    {
        // ---- Build compact facts for prompt
        $facts = trim(implode("\n", array_filter([
            "Product Name: {$name}",
            "Category: {$category}",
            $features ? "Features/Specs:\n{$features}" : null,
            "Primary Market: Nepal",
            "Language: English (include Nepali intent in keywords where useful)",
        ])));

        // ---- Chat prompt with strict instructions
        $messages = [
            [
                'role' => 'system',
                'content' => "You are an expert e-commerce SEO copywriter for Nepal. Return ONLY valid JSON. No commentary."
            ],
            [
                'role' => 'user',
                'content' =>
                    "Using the facts below, generate an SEO pack for a Nepali e-commerce product page.\n\n" .
                    "Rules:\n" .
                    "- meta_title: <= 60 chars; include brand/model if present; sales-friendly, not clickbait.\n" .
                    "- meta_description: 150–160 chars; natural sentence; include 1–2 benefits + CTA that mentions buying in Nepal.\n" .
                    "- meta_keywords: 6–10 comma-separated; avoid duplicates; mix head + long-tail; include Nepal variants (e.g., price in Nepal, buy in Nepal).\n" .
                    "- slug: URL-safe kebab-case from the product name (short but descriptive).\n" .
                    "- Return ONLY JSON with keys: meta_title, meta_description, meta_keywords, slug.\n\n" .
                    "Facts:\n{$facts}"
            ],
        ];

        // ---- Call OpenAI (Chat Completions) with JSON mode and retries
        $payload = [
            'model' => config('services.openai.model', 'gpt-4o-mini'),
            'temperature' => 0.4,
            'response_format' => ['type' => 'json_object'],
            'messages' => $messages,
        ];

        $responseJson = $this->requestWithRetry($payload);

        // Extract text content robustly
        $text = data_get($responseJson, 'choices.0.message.content', '{}');

        // ---- Parse JSON best-effort
        $data = json_decode($text, true);
        if (!is_array($data)) {
            $data = [];
        }

        // ---- Post-process & guardrails

        // Title: clamp to 60, fallback to product name
        $metaTitle = trim((string)($data['meta_title'] ?? ''));
        if ($metaTitle === '') $metaTitle = $name;
        $metaTitle = Str::limit($metaTitle, 60, '');

        // Description: ensure 150–160 and include Nepal CTA
        $metaDesc = trim((string)($data['meta_description'] ?? ''));
        if ($metaDesc === '' || Str::length($metaDesc) < 120) {
            $metaDesc = "Buy {$name} online in Nepal with fast delivery and warranty. Great value and trusted service—order today in Nepal.";
        }
        // Always keep 150–160 chars (Google’s sweet spot)
        if (Str::length($metaDesc) > 160) {
            $metaDesc = Str::limit($metaDesc, 160, '');
        } elseif (Str::length($metaDesc) < 150) {
            // pad gently without changing meaning (rarely needed)
            $metaDesc = rtrim($metaDesc, '.'); 
            $metaDesc .= ". Buy in Nepal today.";
            $metaDesc = Str::limit($metaDesc, 160, '');
        }

        // Keywords: normalize to 6–10 unique items with Nepal context
        $kwRaw = (string)($data['meta_keywords'] ?? '');
        $keywords = $this->normalizeKeywords($kwRaw, $name, $category);

        // Slug: safe kebab-case from product name (or model output)
        $slug = Str::slug($data['slug'] ?? $name);

        return [
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
            'meta_keywords'    => implode(', ', $keywords),
            'slug'             => $slug,
        ];
    }

    /**
     * HTTP call with retries and exponential backoff on 429/5xx.
     */
    protected function requestWithRetry(array $payload, int $maxAttempts = 3): array
    {
        $url = rtrim(config('services.openai.base_url', 'https://api.openai.com'), '/') . '/v1/chat/completions';
        $attempt = 0;
        $waitMs  = 400;

        while (true) {
            $attempt++;
            try {
                $resp = Http::withToken(config('services.openai.key'))
                    ->timeout(30)
                    ->acceptJson()
                    ->asJson()
                    ->post($url, $payload);

                $resp->throw();
                return $resp->json() ?? [];
            } catch (\Throwable $e) {
                $code = (int)($e->getCode() ?: 0);
                $retryable = in_array($code, [0, 408, 429, 500, 502, 503, 504], true);

                if ($attempt >= $maxAttempts || !$retryable) {
                    report($e);
                    return []; // caller will apply fallbacks
                }

                usleep($waitMs * 1000);
                $waitMs *= 2;
            }
        }
    }

    /**
     * Clean, dedupe and shape keywords for Nepal context (6–10 items).
     */
    protected function normalizeKeywords(string $kwRaw, string $name, string $category): array
    {
        // Split, trim, lowercase
        $parts = preg_split('/[,\\n]+/u', $kwRaw) ?: [];
        $parts = array_map(fn($s) => trim(mb_strtolower($s)), $parts);
        $parts = array_filter($parts, fn($s) => $s !== '');

        // Dedupe preserving order
        $unique = [];
        foreach ($parts as $p) {
            if (!in_array($p, $unique, true)) $unique[] = $p;
        }

        // Must-have Nepal-focused variations
        $musts = [
            strtolower("buy {$name} nepal"),
            strtolower("{$name} price in nepal"),
            strtolower("best {$category} in nepal"),
        ];
        foreach ($musts as $m) {
            if (!in_array($m, $unique, true)) $unique[] = $m;
        }

        // If model didn’t add long-tail, enrich with safe defaults
        $defaults = [
            "{$category} deals nepal",
            "cheap {$category} nepal",
            "online {$category} shop nepal",
            "{$category} warranty nepal",
            "{$category} fast delivery nepal",
        ];
        foreach ($defaults as $d) {
            if (count($unique) >= 10) break;
            $d = strtolower($d);
            if (!in_array($d, $unique, true)) $unique[] = $d;
        }

        // Ensure minimum of 6
        if (count($unique) < 6) {
            $pad = [
                "{$name} offers nepal",
                "{$category} price in nepal",
                "{$name} specs nepal",
            ];
            foreach ($pad as $p) {
                if (count($unique) >= 6) break;
                $p = strtolower($p);
                if (!in_array($p, $unique, true)) $unique[] = $p;
            }
        }

        // Final slice 6–10 and tidy presentation case (capitalize first letter only)
        $unique = array_slice($unique, 6 > count($unique) ? 0 : 0, min(max(6, count($unique)), 10));
        return array_map(fn($s) => preg_replace('/\\s+/', ' ', $s), $unique);
    }
}
