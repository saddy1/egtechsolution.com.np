@extends('frontend.layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' | EG Tech Solution')
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('meta_keywords', $post->meta_keywords)
@section('og_type', 'article')
@if ($post->cover_image)
  @section('og_image', asset($post->cover_image))
@endif

@push('structured_data')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->meta_description ?: $post->excerpt,
    'image' => $post->cover_image ? asset($post->cover_image) : asset('assets/eg_tech_logo.png'),
    'datePublished' => optional($post->published_at)->toAtomString(),
    'dateModified' => $post->updated_at->toAtomString(),
    'mainEntityOfPage' => $post->url(),
    'author' => ['@type' => 'Organization', 'name' => 'EG Tech Solution', 'url' => url('/')],
    'publisher' => ['@type' => 'Organization', 'name' => 'EG Tech Solution',
        'logo' => ['@type' => 'ImageObject', 'url' => asset('assets/eg_tech_logo.png')]],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
@unless ($post->isPublished())
  <div class="bg-amber-100 px-4 py-2 text-center text-sm font-medium text-amber-900">
    Draft preview: only admins can see this page. <a href="{{ route('admin.blog.edit', $post) }}" class="underline">Edit post</a>
  </div>
@endunless

<article>
  <header class="bg-brand-navy text-white">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16">
      <nav class="text-sm text-white/70" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-white">Home</a> <span aria-hidden="true">/</span>
        <a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a>
      </nav>
      <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">{{ $post->title }}</h1>
      @if ($post->excerpt)
        <p class="mt-4 text-lg text-white/80">{{ $post->excerpt }}</p>
      @endif
      <p class="mt-5 text-sm text-white/60">
        By EG Tech Solution
        @if ($post->published_at) · <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d M Y') }}</time>@endif
        · {{ $post->readingMinutes() }} min read
      </p>
    </div>
  </header>

  @if ($post->cover_image)
    <div class="mx-auto -mb-4 max-w-4xl px-4 pt-8 sm:px-6">
      <img src="{{ asset($post->cover_image) }}" alt="{{ $post->title }}" class="aspect-[1200/630] w-full rounded-2xl object-cover">
    </div>
  @endif

  <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <div class="prose-blog">{!! $post->body !!}</div>

    @if (!empty($post->sources))
      <aside class="mt-10 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm">
        <h2 class="font-semibold text-slate-900">Sources</h2>
        <ul class="mt-2 space-y-1">
          @foreach ($post->sources as $s)
            <li><a href="{{ $s['url'] }}" target="_blank" rel="nofollow noopener" class="text-brand-navy underline hover:text-brand-orange">{{ $s['site'] }}: {{ $s['title'] }}</a></li>
          @endforeach
        </ul>
      </aside>
    @endif

    {{-- Call to action --}}
    <aside class="mt-10 flex flex-col gap-4 rounded-2xl bg-brand-navy p-6 text-white sm:flex-row sm:items-center sm:justify-between">
      <div>
        <p class="text-lg font-bold">Need help choosing?</p>
        <p class="text-sm text-white/75">Ask our team in Dhangadhi for advice and the latest price.</p>
      </div>
      <div class="flex flex-wrap gap-2">
        <a href="https://wa.me/9779864830276" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-brand-navy hover:bg-slate-100">
          <i class="fa-brands fa-whatsapp text-emerald-600" aria-hidden="true"></i> WhatsApp
        </a>
        <a href="tel:+9779864830276" class="inline-flex items-center gap-2 rounded-xl bg-brand-orange px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-orange-dark">
          <i class="fa-solid fa-phone" aria-hidden="true"></i> Call us
        </a>
      </div>
    </aside>
  </div>
</article>

@if ($related->isNotEmpty())
  <section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold tracking-tight text-slate-900">Products in this article</h2>
      <div class="mt-6 grid grid-cols-2 gap-4 sm:gap-6 md:grid-cols-3 lg:grid-cols-4">
        @foreach ($related as $product)
          @include('frontend.partials.product-card', ['product' => $product])
        @endforeach
      </div>
    </div>
  </section>
@endif

@if ($more->isNotEmpty())
  <section class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold tracking-tight text-slate-900">More from the blog</h2>
      <div class="mt-6 grid gap-6 sm:grid-cols-3">
        @foreach ($more as $m)
          <a href="{{ $m->url() }}" class="rounded-2xl border border-slate-200 p-5 transition hover:-translate-y-1 hover:shadow-lg">
            <p class="text-xs text-slate-500">{{ $m->published_at->format('d M Y') }}</p>
            <p class="mt-1 font-semibold leading-snug text-slate-900">{{ $m->title }}</p>
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endif
@endsection
