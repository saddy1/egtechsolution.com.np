@extends('frontend.layouts.app')

@section('title', 'Tech Blog: Buying Guides & News for Nepal | EG Tech Solution')
@section('meta_description', 'Laptop and gadget buying guides, price tips and tech news for Nepal from EG Tech Solution, your computer shop in Dhangadhi, Kailali.')
@section('meta_keywords', 'laptop buying guide Nepal, tech news Nepal, computer tips Dhangadhi, EG Tech blog')

@section('content')
<section class="bg-brand-navy text-white">
  <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
    <p class="text-sm font-semibold uppercase tracking-wider text-brand-orange">EG Tech blog</p>
    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">Buying guides &amp; tech news for Nepal</h1>
    <p class="mt-3 max-w-2xl text-white/75">Honest advice on laptops, PCs, printers and gadgets from our team in Dhangadhi.</p>
  </div>
</section>

<section class="bg-slate-50 py-12 sm:py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    @if ($posts->isEmpty())
      <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">
        Our first articles are on the way. <a href="{{ route('shop') }}" class="font-semibold text-brand-navy underline">Browse products</a> in the meantime.
      </p>
    @else
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($posts as $post)
          <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-1 hover:shadow-lg">
            <a href="{{ $post->url() }}" class="block aspect-[1200/630] overflow-hidden bg-brand-navy">
              @if ($post->cover_image)
                <img src="{{ asset($post->cover_image) }}" alt="" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
              @else
                {{-- No cover: branded placeholder with the title --}}
                <div class="flex h-full items-end bg-[radial-gradient(ellipse_at_top_right,_#0b4a8a_0%,_#002e5b_55%,_#001d3a_100%)] p-5">
                  <span class="line-clamp-3 text-lg font-bold leading-snug text-white">{{ $post->title }}</span>
                </div>
              @endif
            </a>
            <div class="flex flex-1 flex-col p-5">
              <p class="text-xs text-slate-500">
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d M Y') }}</time>
                · {{ $post->readingMinutes() }} min read
              </p>
              <h2 class="mt-2 text-lg font-bold leading-snug text-slate-900">
                <a href="{{ $post->url() }}" class="hover:text-brand-navy">{{ $post->title }}</a>
              </h2>
              @if ($post->excerpt)
                <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">{{ $post->excerpt }}</p>
              @endif
              <a href="{{ $post->url() }}" class="mt-auto pt-4 text-sm font-semibold text-brand-orange hover:text-brand-orange-dark">
                Read article <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
          </article>
        @endforeach
      </div>
      <div class="mt-10">{{ $posts->links() }}</div>
    @endif
  </div>
</section>
@endsection
