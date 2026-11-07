<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /** XML sitemap submitted to Google Search Console. */
    public function sitemap(): Response
    {
        $pages = [
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('about'), 'priority' => '0.6'],
            ['loc' => route('contact'), 'priority' => '0.7'],
            ['loc' => route('shop'), 'priority' => '0.9'],
            ['loc' => route('blog.index'), 'priority' => '0.7'],
        ];

        $posts = \App\Models\Post::published()->latest('published_at')->get(['slug', 'updated_at']);

        $products = Product::query()
            ->where('is_active', true)
            ->listed()
            ->latest('updated_at')
            ->get(['id', 'slug', 'updated_at']);

        return response()
            ->view('seo.sitemap', compact('pages', 'products', 'posts'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /** Keep crawling enabled and advertise the sitemap to search engines. */
    public function robots(): Response
    {
        return response("User-agent: *\nAllow: /\n\nSitemap: ".route('sitemap')."\n", 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
