@extends('backend.layout.app')

@section('content')
@php $isNew = !$post->exists; @endphp
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-6xl space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <a href="{{ route('admin.blog.index') }}" class="text-sm text-gray-500 hover:text-gray-800"><i class="fas fa-arrow-left"></i> All posts</a>
        <h5 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">{{ $isNew ? 'New post' : 'Edit post' }}</h5>
      </div>
      @unless ($isNew)
        <a href="{{ $post->url() }}" target="_blank"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
          <i class="fas fa-eye"></i> {{ $post->isPublished() ? 'View live' : 'Preview draft' }}
        </a>
      @endunless
    </div>

    @if (session('success'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
      <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        <ul class="list-disc pl-5">@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
      </div>
    @endif

    @if ($post->ai_generated && !$post->isPublished())
      <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
        <strong>AI draft. Check before publishing:</strong> prices, specs and Nepal availability are correct,
        the product links make sense, and the text reads naturally. Edit anything that's wrong.
      </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $isNew ? route('admin.blog.store') : route('admin.blog.update', $post) }}"
          class="grid gap-6 lg:grid-cols-3">
      @csrf
      @unless ($isNew) @method('PUT') @endunless

      {{-- Main --}}
      <div class="space-y-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
          <input id="title" name="title" required maxlength="180" value="{{ old('title', $post->title) }}"
                 class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-base font-semibold">
        </div>
        <div>
          <label for="excerpt" class="block text-sm font-medium text-gray-700">Short summary</label>
          <textarea id="excerpt" name="excerpt" rows="2" maxlength="300"
                    class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>
        <div>
          <div class="flex items-center justify-between">
            <label for="body" class="block text-sm font-medium text-gray-700">Article (HTML)</label>
            <button type="button" id="togglePreview" class="text-sm font-medium text-indigo-600 hover:underline">Show preview</button>
          </div>
          <textarea id="body" name="body" rows="24" required
                    class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-3 font-mono text-xs leading-5">{{ old('body', $post->body) }}</textarea>
          <div id="bodyPreview" class="prose-blog mt-1 hidden max-h-[40rem] overflow-y-auto rounded-xl border border-gray-200 p-5"></div>
          <p class="mt-1 text-xs text-gray-500">Allowed: &lt;h2&gt; &lt;h3&gt; &lt;p&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;strong&gt; &lt;em&gt; &lt;a href&gt; &lt;table&gt; &lt;blockquote&gt;. Anything else is removed on save.</p>
        </div>
      </div>

      {{-- Side --}}
      <div class="space-y-6">
        <div class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h6 class="font-semibold text-gray-900">Publish</h6>
          @php $status = old('status', $post->status ?? 'draft'); @endphp
          <label class="flex items-center gap-2 text-sm"><input type="radio" name="status" value="draft" {{ $status === 'draft' ? 'checked' : '' }}> Draft (hidden)</label>
          <label class="flex items-center gap-2 text-sm"><input type="radio" name="status" value="published" {{ $status === 'published' ? 'checked' : '' }}> Published (live on /blog)</label>
          @if ($post->published_at)
            <p class="text-xs text-gray-500">First published {{ $post->published_at->format('d M Y, h:i A') }}</p>
          @endif
          <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Save post</button>
        </div>

        <div class="space-y-3 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h6 class="font-semibold text-gray-900">Cover image</h6>
          @if ($post->cover_image)
            <img src="{{ asset($post->cover_image) }}" alt="" class="aspect-video w-full rounded-lg object-cover">
            <label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="remove_cover" value="1"> Remove image</label>
          @endif
          <input type="file" name="cover" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm">
          <p class="text-xs text-gray-500">Optional. Use your own product photo; 1200×630 works best for Facebook shares.</p>
        </div>

        <div class="space-y-3 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h6 class="font-semibold text-gray-900">SEO</h6>
          <div>
            <label for="slug" class="block text-xs font-medium text-gray-600">URL slug</label>
            <input id="slug" name="slug" value="{{ old('slug', $post->slug) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
          </div>
          <div>
            <label for="meta_title" class="block text-xs font-medium text-gray-600">Meta title <span class="text-gray-400">(≤ 60)</span></label>
            <input id="meta_title" name="meta_title" maxlength="70" value="{{ old('meta_title', $post->meta_title) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
          </div>
          <div>
            <label for="meta_description" class="block text-xs font-medium text-gray-600">Meta description <span class="text-gray-400">(140–160)</span></label>
            <textarea id="meta_description" name="meta_description" rows="3" maxlength="320" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('meta_description', $post->meta_description) }}</textarea>
          </div>
          <div>
            <label for="meta_keywords" class="block text-xs font-medium text-gray-600">Keywords</label>
            <input id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
          </div>
        </div>

        @if (!empty($post->sources))
          <div class="space-y-2 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h6 class="font-semibold text-gray-900">News sources used</h6>
            <ul class="space-y-1 text-sm">
              @foreach ($post->sources as $s)
                <li><a href="{{ $s['url'] }}" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">{{ $s['site'] }}: {{ $s['title'] }}</a></li>
              @endforeach
            </ul>
            <p class="text-xs text-gray-500">Shown as "Sources" at the bottom of the post.</p>
          </div>
        @endif
      </div>
    </form>
  </div>
</section>

<style>
  .prose-blog h2 { font-size: 1.25rem; font-weight: 700; margin: 1.25rem 0 .5rem; }
  .prose-blog h3 { font-size: 1.05rem; font-weight: 600; margin: 1rem 0 .4rem; }
  .prose-blog p { margin: .6rem 0; line-height: 1.7; }
  .prose-blog ul { list-style: disc; padding-left: 1.4rem; } .prose-blog ol { list-style: decimal; padding-left: 1.4rem; }
  .prose-blog a { color: #4f46e5; text-decoration: underline; }
  .prose-blog table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: .875rem; }
  .prose-blog th, .prose-blog td { border: 1px solid #e5e7eb; padding: .4rem .6rem; text-align: left; }
</style>
<script>
  (function () {
    const btn = document.getElementById('togglePreview'), body = document.getElementById('body'), prev = document.getElementById('bodyPreview');
    btn.addEventListener('click', () => {
      const showing = !prev.classList.contains('hidden');
      if (!showing) {
        // Preview only: render into an inert document so scripts never run
        const doc = new DOMParser().parseFromString(body.value, 'text/html');
        doc.querySelectorAll('script,style,iframe,object,embed,img,svg,form').forEach(n => n.remove());
        doc.body.querySelectorAll('*').forEach(el => [...el.attributes].forEach(a => {
          if (a.name.startsWith('on') || (a.name === 'href' && /^\s*javascript:/i.test(a.value))) el.removeAttribute(a.name);
        }));
        prev.replaceChildren(...doc.body.childNodes);
      }
      prev.classList.toggle('hidden', showing);
      body.classList.toggle('hidden', !showing);
      btn.textContent = showing ? 'Show preview' : 'Back to editing';
    });
  })();
</script>
@endsection
