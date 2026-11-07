<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\BlogWriterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/** Admin: generate blog drafts with AI, edit them, and publish. */
class BlogPostController extends Controller
{
    public function index(BlogWriterService $writer)
    {
        $posts = Post::latest()->paginate(20);

        // Show today's headlines so the admin can see what the AI will pick from
        $headlines = rescue(fn () => $writer->latestHeadlines(10), [], false);

        return view('backend.blog.index', compact('posts', 'headlines'));
    }

    public function generate(Request $request, BlogWriterService $writer)
    {
        $data = $request->validate(['topic' => ['nullable', 'string', 'max:200']]);

        @set_time_limit(240);

        try {
            $post = $writer->generate($data['topic'] ?? null);
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', $e instanceof \RuntimeException ? $e->getMessage() : 'Generating the post failed. Please try again.');
        }

        return redirect()->route('admin.blog.edit', $post)
            ->with('success', 'Draft created. Check the facts and prices, then publish.');
    }

    public function create()
    {
        return view('backend.blog.edit', ['post' => new Post(['status' => 'draft'])]);
    }

    public function store(Request $request, BlogWriterService $writer)
    {
        $post = new Post();
        $this->fill($post, $request, $writer);
        return redirect()->route('admin.blog.edit', $post)->with('success', 'Post saved.');
    }

    public function edit(Post $post)
    {
        return view('backend.blog.edit', compact('post'));
    }

    public function update(Request $request, Post $post, BlogWriterService $writer)
    {
        $this->fill($post, $request, $writer);
        return redirect()->route('admin.blog.edit', $post)
            ->with('success', $post->isPublished() ? 'Post saved and live.' : 'Post saved.');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) File::delete(public_path($post->cover_image));
        $post->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Post deleted.');
    }

    private function fill(Post $post, Request $request, BlogWriterService $writer): void
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:180'],
            'slug'             => ['nullable', 'string', 'max:190'],
            'excerpt'          => ['nullable', 'string', 'max:300'],
            'body'             => ['required', 'string'],
            'meta_title'       => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'meta_keywords'    => ['nullable', 'string', 'max:500'],
            'cover'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_cover'     => ['nullable', 'boolean'],
            'status'           => ['required', 'in:draft,published'],
        ]);

        $post->fill([
            'title'            => $data['title'],
            'slug'             => Post::uniqueSlug($data['slug'] ?: $data['title'], $post->id),
            'excerpt'          => $data['excerpt'] ?? null,
            'body'             => $writer->sanitize($data['body']),
            'meta_title'       => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords'    => $data['meta_keywords'] ?? null,
            'status'           => $data['status'],
        ]);

        // First publish stamps the date; going back to draft keeps it for later.
        if ($post->status === 'published' && !$post->published_at) {
            $post->published_at = now();
        }

        if (!empty($data['remove_cover']) && $post->cover_image) {
            File::delete(public_path($post->cover_image));
            $post->cover_image = null;
        }

        if ($request->hasFile('cover')) {
            if ($post->cover_image) File::delete(public_path($post->cover_image));
            $file = $request->file('cover');
            $name = Str::slug(Str::limit($post->slug, 60, '')) . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('blog'), $name);
            $post->cover_image = 'blog/' . $name;
        }

        $post->save();
    }
}
