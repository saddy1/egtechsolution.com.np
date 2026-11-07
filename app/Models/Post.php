<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'cover_image',
        'meta_title', 'meta_description', 'meta_keywords',
        'sources', 'related_product_ids', 'status', 'ai_generated', 'published_at',
    ];

    protected $casts = [
        'sources' => 'array',
        'related_product_ids' => 'array',
        'ai_generated' => 'bool',
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at && $this->published_at->lte(now());
    }

    public function url(): string
    {
        return route('blog.show', $this->slug);
    }

    /** Products the article recommends, in stock order of the saved ids. */
    public function relatedProducts()
    {
        $ids = array_filter(array_map('intval', $this->related_product_ids ?? []));
        if (!$ids) return collect();

        return Product::whereIn('id', $ids)->where('is_active', true)->get()
            ->sortBy(fn ($p) => array_search($p->id, $ids))->values();
    }

    /** Rough reading time for the post header. */
    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200));
    }

    public static function uniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug(Str::limit($text, 80, '')) ?: 'post';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}
