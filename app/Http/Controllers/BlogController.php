<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

/** Public blog pages. */
class BlogController extends Controller
{
    private function cartCount(): int
    {
        return auth()->check() ? Cart::where('user_id', auth()->id())->count() : 0;
    }

    public function index()
    {
        $posts = Post::published()->latest('published_at')->paginate(12);
        $cartCount = $this->cartCount();

        return view('frontend.blog.index', compact('posts', 'cartCount'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Drafts are only visible to a logged-in admin (for preview)
        if (!$post->isPublished() && !Auth::guard('admin')->check()) {
            abort(404);
        }

        $related = $post->relatedProducts();
        $more = Post::published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();
        $cartCount = $this->cartCount();

        return view('frontend.blog.show', compact('post', 'related', 'more', 'cartCount'));
    }
}
