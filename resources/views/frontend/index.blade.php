@extends('frontend.layouts.app')

@section('title', $meta_title)
@section('meta_description', $meta_description)
@section('meta_keywords', $meta_keywords)

@php
  // ===== Shop details: edit these in one place =====
  $phone      = '+9779864830276';
  $phoneLabel = '+977 986-4830276';
  $whatsapp   = 'https://wa.me/9779864830276';
  $hours      = 'Sun – Fri, 9:00 AM – 7:00 PM'; // PLACEHOLDER: confirm opening hours
  $mapQuery   = urlencode('EG Tech Solution, Dhangadhi, Kailali, Nepal');

  // PLACEHOLDER numbers: replace with real figures
  $stats = [
      ['value' => 8,    'suffix' => '+', 'label' => 'Years serving Kailali'],
      ['value' => 5000, 'suffix' => '+', 'label' => 'Happy customers'],
      ['value' => 30,   'suffix' => '+', 'label' => 'Trusted brands'],
      ['value' => 100,  'suffix' => '%', 'label' => 'Genuine products'],
  ];

  $trust = [
      ['icon' => 'fa-shield-halved',   'title' => '100% genuine',        'text' => 'Original products from authorised distributors.'],
      ['icon' => 'fa-file-contract',   'title' => 'Official warranty',   'text' => 'Brand warranty with every laptop and PC.'],
      ['icon' => 'fa-truck-fast',      'title' => 'Delivery in Kailali', 'text' => 'Fast delivery across Dhangadhi and nearby.'],
      ['icon' => 'fa-screwdriver-wrench', 'title' => 'Repair & support', 'text' => 'Setup, servicing and after-sales help.'],
  ];

  // Pick an icon for each category from its name
  $categoryIcon = function (string $name) {
      $n = strtolower($name);
      $map = [
          'laptop' => 'fa-laptop', 'notebook' => 'fa-laptop', 'desktop' => 'fa-desktop', 'pc' => 'fa-desktop',
          'computer' => 'fa-desktop', 'monitor' => 'fa-tv', 'printer' => 'fa-print', 'scanner' => 'fa-print',
          'mobile' => 'fa-mobile-screen', 'phone' => 'fa-mobile-screen', 'tablet' => 'fa-tablet-screen-button',
          'head' => 'fa-headphones', 'ear' => 'fa-headphones', 'audio' => 'fa-headphones', 'speaker' => 'fa-volume-high',
          'keyboard' => 'fa-keyboard', 'mouse' => 'fa-computer-mouse', 'gaming' => 'fa-gamepad', 'game' => 'fa-gamepad',
          'camera' => 'fa-camera', 'cctv' => 'fa-video', 'network' => 'fa-wifi', 'router' => 'fa-wifi', 'wifi' => 'fa-wifi',
          'storage' => 'fa-hard-drive', 'ssd' => 'fa-hard-drive', 'hdd' => 'fa-hard-drive', 'pendrive' => 'fa-usb',
          'ups' => 'fa-plug', 'power' => 'fa-plug', 'cable' => 'fa-plug', 'ram' => 'fa-memory', 'memory' => 'fa-memory',
          'software' => 'fa-compact-disc', 'accessor' => 'fa-plug-circle-check', 'watch' => 'fa-clock',
      ];
      foreach ($map as $key => $icon) {
          if (str_contains($n, $key)) return $icon;
      }
      return 'fa-microchip';
  };

  $visibleCategories = ($categories ?? collect())->filter(fn ($c) => $c->products_count > 0)->sortByDesc('products_count')->take(8);
  $brandNames = ($brands ?? collect())->filter(fn ($b) => $b->products_count > 0)->pluck('name');
@endphp

@section('content')

{{-- ================= HERO ================= --}}
<section class="relative isolate overflow-hidden bg-brand-navy text-white">
  {{-- Background: gradient, dot grid and animated circuit lines --}}
  <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,_#0b4a8a_0%,_#002e5b_45%,_#001d3a_100%)]"></div>
  <div class="absolute inset-0 -z-10 opacity-[0.12] [background-image:radial-gradient(#fff_1px,transparent_1px)] [background-size:22px_22px]"></div>
  <svg class="absolute inset-0 -z-10 hidden h-full w-full sm:block" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <g fill="none" stroke="#ffffff" stroke-opacity=".10" stroke-width="1.5">
      <path d="M0 120 H260 L300 160 H520"/>
      <path d="M0 470 H180 L230 420 H420 L450 450 H640"/>
      <path d="M1200 90 H980 L940 130 H760"/>
      <path d="M1200 520 H1020 L980 480 H820 L790 510 H700"/>
      <path d="M620 0 V70 L660 110 V200"/>
    </g>
    <g fill="none" stroke="#f26b1d" stroke-width="2" stroke-linecap="round">
      <path class="circuit-pulse" d="M0 120 H260 L300 160 H520"/>
      <path class="circuit-pulse" style="animation-delay:-2s" d="M0 470 H180 L230 420 H420 L450 450 H640"/>
      <path class="circuit-pulse" style="animation-delay:-1s" d="M1200 90 H980 L940 130 H760"/>
      <path class="circuit-pulse" style="animation-delay:-3.5s" d="M1200 520 H1020 L980 480 H820 L790 510 H700"/>
    </g>
    <g fill="#f26b1d" fill-opacity=".6">
      <circle cx="520" cy="160" r="4"/><circle cx="640" cy="450" r="4"/><circle cx="760" cy="130" r="4"/><circle cx="700" cy="510" r="4"/>
    </g>
  </svg>

  <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-14 sm:px-6 sm:py-20 lg:grid-cols-2 lg:px-8 lg:py-24">
    {{-- Copy --}}
    <div class="reveal">
      <p class="inline-flex items-center gap-2 rounded-full border border-white/20 px-3 py-1 text-xs font-medium text-white/90 sm:text-sm">
        <span class="h-2 w-2 rounded-full bg-brand-orange"></span>
        Computer &amp; electronics shop in Dhangadhi
      </p>
      <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
        <span class="sr-only">EG Tech Solution, computer shop in Dhangadhi: </span>
        Laptops, PCs &amp; tech that
        <span class="text-brand-orange">just works.</span>
      </h1>
      <p class="mt-5 max-w-xl text-base leading-7 text-white/80 sm:text-lg">
        Genuine laptops, desktops, printers and accessories with official warranty,
        honest advice and local support. Delivered across Kailali.
      </p>

      <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('shop') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-orange px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-orange-dark sm:text-base">
          Shop products <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
        <a href="{{ $whatsapp }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-brand-navy transition hover:bg-slate-100 sm:text-base">
          <i class="fa-brands fa-whatsapp text-lg text-emerald-600" aria-hidden="true"></i> Ask on WhatsApp
        </a>
        <a href="tel:{{ $phone }}"
           class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10 sm:text-base">
          <i class="fa-solid fa-phone" aria-hidden="true"></i> Call us
        </a>
      </div>

      <ul class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-white/75">
        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-brand-orange" aria-hidden="true"></i> Official warranty</li>
        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-brand-orange" aria-hidden="true"></i> Genuine products</li>
        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-brand-orange" aria-hidden="true"></i> After-sales service</li>
      </ul>
    </div>

    {{-- Illustration: laptop with floating gadgets --}}
    <div class="relative mx-auto aspect-[4/3] w-full max-w-xs sm:max-w-lg" aria-hidden="true">
      <div class="glow-pulse absolute inset-[12%] rounded-full bg-brand-orange/40 blur-3xl"></div>

      <svg viewBox="0 0 400 300" class="relative h-full w-full drop-shadow-2xl">
        {{-- Screen --}}
        <rect x="70" y="40" width="260" height="165" rx="12" fill="#0f172a" stroke="#cbd5e1" stroke-width="4"/>
        <rect x="82" y="52" width="236" height="141" rx="4" fill="#0b2545"/>
        {{-- Screen content: little dashboard --}}
        <rect x="96" y="66" width="90" height="10" rx="3" fill="#f26b1d"/>
        <rect x="96" y="84" width="140" height="6" rx="3" fill="#ffffff" fill-opacity=".35"/>
        <rect x="96" y="96" width="110" height="6" rx="3" fill="#ffffff" fill-opacity=".25"/>
        <rect x="96" y="118" width="60" height="58" rx="6" fill="#ffffff" fill-opacity=".08"/>
        <rect x="164" y="118" width="60" height="58" rx="6" fill="#ffffff" fill-opacity=".08"/>
        <rect x="232" y="118" width="72" height="58" rx="6" fill="#ffffff" fill-opacity=".08"/>
        <rect x="104" y="150" width="8" height="18" rx="2" fill="#f26b1d"/>
        <rect x="116" y="140" width="8" height="28" rx="2" fill="#f26b1d" fill-opacity=".8"/>
        <rect x="128" y="132" width="8" height="36" rx="2" fill="#f26b1d" fill-opacity=".6"/>
        <circle cx="194" cy="147" r="16" fill="none" stroke="#ffffff" stroke-opacity=".3" stroke-width="6"/>
        <path d="M194 131 a16 16 0 0 1 16 16" fill="none" stroke="#f26b1d" stroke-width="6"/>
        <rect class="caret-blink" x="244" y="66" width="3" height="12" fill="#ffffff"/>
        {{-- Base --}}
        <path d="M40 210 H360 L372 228 Q372 236 362 236 H38 Q28 236 28 228 Z" fill="#e2e8f0"/>
        <rect x="170" y="212" width="60" height="6" rx="3" fill="#94a3b8"/>
      </svg>

      {{-- Floating gadget chips --}}
      <div class="float-slow absolute left-0 top-[8%] flex items-center gap-2 rounded-2xl bg-white px-3 py-2 text-brand-navy shadow-xl">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-orange/10 text-brand-orange"><i class="fa-solid fa-headphones"></i></span>
        <span class="hidden text-xs font-semibold sm:block">Audio</span>
      </div>
      <div class="float-mid absolute right-0 top-[2%] flex items-center gap-2 rounded-2xl bg-white px-3 py-2 text-brand-navy shadow-xl" style="animation-delay:-1.5s">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-navy/10 text-brand-navy"><i class="fa-solid fa-mobile-screen"></i></span>
        <span class="hidden text-xs font-semibold sm:block">Mobiles</span>
      </div>
      <div class="float-fast absolute bottom-[6%] left-[4%] flex items-center gap-2 rounded-2xl bg-white px-3 py-2 text-brand-navy shadow-xl" style="animation-delay:-.8s">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-navy/10 text-brand-navy"><i class="fa-solid fa-print"></i></span>
        <span class="hidden text-xs font-semibold sm:block">Printers</span>
      </div>
      <div class="float-slow absolute bottom-[12%] right-[2%] flex items-center gap-2 rounded-2xl bg-white px-3 py-2 text-brand-navy shadow-xl" style="animation-delay:-3s">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-orange/10 text-brand-orange"><i class="fa-solid fa-gamepad"></i></span>
        <span class="hidden text-xs font-semibold sm:block">Gaming</span>
      </div>
    </div>
  </div>
</section>

{{-- ================= TRUST BAR ================= --}}
<section class="border-b border-slate-200 bg-white">
  <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 py-8 sm:px-6 lg:grid-cols-4 lg:px-8">
    @foreach ($trust as $t)
      <div class="reveal flex items-start gap-3" style="transition-delay: {{ $loop->index * 80 }}ms">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-navy/5 text-lg text-brand-navy">
          <i class="fa-solid {{ $t['icon'] }}" aria-hidden="true"></i>
        </span>
        <div>
          <h2 class="text-sm font-semibold text-slate-900">{{ $t['title'] }}</h2>
          <p class="mt-0.5 text-xs leading-5 text-slate-600 sm:text-sm">{{ $t['text'] }}</p>
        </div>
      </div>
    @endforeach
  </div>
</section>

{{-- ================= CATEGORIES ================= --}}
@if ($visibleCategories->isNotEmpty())
<section class="bg-slate-50 py-14 sm:py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="reveal flex items-end justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Shop by category</h2>
        <p class="mt-1 text-slate-600">Find what you need in a click.</p>
      </div>
      <a href="{{ route('shop') }}" class="hidden shrink-0 text-sm font-semibold text-brand-navy hover:text-brand-orange sm:block">
        All products <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>

    <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
      @foreach ($visibleCategories as $cat)
        <a href="{{ route('shop', ['category' => [$cat->id]]) }}"
           class="reveal group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-brand-orange/40 hover:shadow-lg"
           style="transition-delay: {{ $loop->index * 60 }}ms">
          <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-navy text-lg text-white transition group-hover:bg-brand-orange">
            <i class="fa-solid {{ $categoryIcon($cat->name) }}" aria-hidden="true"></i>
          </span>
          <span class="min-w-0">
            <span class="block truncate font-semibold text-slate-900">{{ $cat->name }}</span>
            <span class="text-xs text-slate-500">{{ $cat->products_count }} {{ Str::plural('product', $cat->products_count) }}</span>
          </span>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ================= FEATURED PRODUCTS ================= --}}
<section class="bg-white py-14 sm:py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="reveal flex items-end justify-between gap-4">
      <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-brand-orange">Featured</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Our top picks this week</h2>
      </div>
      <a href="{{ route('shop') }}" class="hidden shrink-0 text-sm font-semibold text-brand-navy hover:text-brand-orange sm:block">
        View all <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>

    <div class="mt-8 grid grid-cols-2 gap-4 sm:gap-6 md:grid-cols-3 lg:grid-cols-4">
      @forelse ($featuredProducts as $product)
        <div class="reveal" style="transition-delay: {{ ($loop->index % 4) * 70 }}ms">
          @include('frontend.partials.product-card', ['product' => $product])
        </div>
      @empty
        <p class="col-span-full rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
          Featured products are coming soon. <a href="{{ route('shop') }}" class="font-semibold text-brand-navy underline">Browse all products</a>
        </p>
      @endforelse
    </div>
  </div>
</section>

{{-- ================= OFFERS (banner slider) ================= --}}
@if (($banners ?? collect())->isNotEmpty())
<section class="bg-slate-50 py-14 sm:py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="reveal mb-8">
      <p class="text-sm font-semibold uppercase tracking-wider text-brand-orange">Offers</p>
      <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Current deals</h2>
    </div>

    <div id="hero" class="reveal relative overflow-hidden rounded-2xl bg-slate-200"
         data-carousel="slide" data-autoplay="true" data-interval="5000"
         role="region" aria-roledescription="carousel" aria-label="Current offers">
      @foreach ($banners as $i => $b)
        <div class="{{ $i === 0 ? 'block' : 'hidden' }}" data-carousel-item
             role="group" aria-roledescription="slide" aria-label="{{ $i + 1 }} of {{ $banners->count() }}">
          @if ($b->link_url)<a href="{{ $b->link_url }}" class="block">@endif
            <img src="{{ asset($b->image_path) }}" alt="{{ $b->alt_text ?? $b->name }}"
                 class="aspect-[16/7] w-full object-cover" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
          @if ($b->link_url)</a>@endif
        </div>
      @endforeach

      @if ($banners->count() > 1)
        <button type="button" data-carousel-prev aria-label="Previous offer"
                class="absolute left-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-brand-navy shadow transition hover:bg-white">
          <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button type="button" data-carousel-next aria-label="Next offer"
                class="absolute right-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-brand-navy shadow transition hover:bg-white">
          <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
        </button>
        <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-2">
          @foreach ($banners as $i => $b)
            <button type="button" data-carousel-slide-to="{{ $i }}" aria-label="Show offer {{ $i + 1 }}"
                    aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                    class="h-2.5 w-2.5 rounded-full bg-white/30 ring-1 ring-black/10"></button>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</section>
@endif

{{-- ================= MORE PRODUCTS ================= --}}
@if (($Randproducts ?? collect())->isNotEmpty())
<section class="bg-white py-14 sm:py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="reveal flex items-end justify-between gap-4">
      <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-brand-orange">Explore</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">More from our store</h2>
      </div>
      <a href="{{ route('shop') }}" class="hidden shrink-0 text-sm font-semibold text-brand-navy hover:text-brand-orange sm:block">
        View all <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>

    <div class="mt-8 grid grid-cols-2 gap-4 sm:gap-6 md:grid-cols-3 lg:grid-cols-4">
      @foreach ($Randproducts->take(8) as $product)
        <div class="reveal" style="transition-delay: {{ ($loop->index % 4) * 70 }}ms">
          @include('frontend.partials.product-card', ['product' => $product])
        </div>
      @endforeach
    </div>

    <div class="mt-10 text-center">
      <a href="{{ route('shop') }}"
         class="inline-flex items-center gap-2 rounded-xl border border-brand-navy px-6 py-3 text-sm font-semibold text-brand-navy transition hover:bg-brand-navy hover:text-white">
        Browse all products <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>
@endif

{{-- ================= BRANDS ================= --}}
@if ($brandNames->isNotEmpty())
<section class="border-y border-slate-200 bg-slate-50 py-10" aria-label="Brands we sell">
  <p class="text-center text-sm font-semibold uppercase tracking-wider text-slate-500">Brands we sell</p>
  <div class="marquee relative mt-6 overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]">
    <ul class="marquee-track flex w-max gap-4">
      {{-- Listed twice so the loop is seamless --}}
      @foreach ([$brandNames, $brandNames] as $set)
        @foreach ($set as $name)
          <li @if ($loop->parent->index === 1) aria-hidden="true" @endif>
            <a href="{{ route('shop', ['search' => $name]) }}" @if ($loop->parent->index === 1) tabindex="-1" @endif
               class="block whitespace-nowrap rounded-xl border border-slate-200 bg-white px-6 py-3 text-lg font-bold tracking-tight text-slate-500 transition hover:border-brand-navy hover:text-brand-navy">
              {{ $name }}
            </a>
          </li>
        @endforeach
      @endforeach
    </ul>
  </div>
</section>
@endif

{{-- ================= WHY US + STATS ================= --}}
<section class="relative overflow-hidden bg-brand-navy py-14 text-white sm:py-16">
  <div class="absolute inset-0 opacity-[0.08] [background-image:radial-gradient(#fff_1px,transparent_1px)] [background-size:22px_22px]" aria-hidden="true"></div>
  <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="reveal mx-auto max-w-2xl text-center">
      <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Why Dhangadhi shops with EG Tech</h2>
      <p class="mt-3 text-white/75">Straight advice, fair prices and a team that's still here after you buy.</p>
    </div>
    <dl class="mt-10 grid grid-cols-2 gap-6 lg:grid-cols-4">
      @foreach ($stats as $s)
        <div class="reveal flex flex-col rounded-2xl border border-white/10 bg-white/5 p-6 text-center" style="transition-delay: {{ $loop->index * 80 }}ms">
          <dt class="order-2 mt-1 text-sm text-white/70">{{ $s['label'] }}</dt>
          <dd class="text-3xl font-extrabold text-brand-orange sm:text-4xl">
            <span data-count-to="{{ $s['value'] }}">{{ number_format($s['value']) }}</span>{{ $s['suffix'] }}
          </dd>
        </div>
      @endforeach
    </dl>
  </div>
</section>

{{-- ================= VISIT US ================= --}}
<section class="bg-white py-14 sm:py-16">
  <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
    <div class="reveal">
      <p class="text-sm font-semibold uppercase tracking-wider text-brand-orange">Visit us</p>
      <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Come see it before you buy</h2>
      <p class="mt-3 text-slate-600">Try laptops, compare printers and get honest advice in our Dhangadhi store.</p>

      <img src="{{ asset('assets/egtech_shop_1200.jpg') }}" alt="Inside the EG Tech Solution store in Dhangadhi"
           loading="lazy" width="1200" height="900"
           class="mt-6 aspect-[4/3] w-full rounded-2xl object-cover">
    </div>

    <div class="reveal flex flex-col gap-6">
      <ul class="space-y-4 rounded-2xl border border-slate-200 p-6">
        <li class="flex gap-3">
          <i class="fa-solid fa-location-dot mt-1 w-5 text-brand-orange" aria-hidden="true"></i>
          <div><p class="font-semibold text-slate-900">Address</p><p class="text-slate-600">Dhangadhi, Kailali, Nepal</p></div>
        </li>
        <li class="flex gap-3">
          <i class="fa-solid fa-clock mt-1 w-5 text-brand-orange" aria-hidden="true"></i>
          <div><p class="font-semibold text-slate-900">Opening hours</p><p class="text-slate-600">{{ $hours }}</p></div>
        </li>
        <li class="flex gap-3">
          <i class="fa-solid fa-phone mt-1 w-5 text-brand-orange" aria-hidden="true"></i>
          <div><p class="font-semibold text-slate-900">Phone</p><a href="tel:{{ $phone }}" class="text-slate-600 hover:text-brand-navy">{{ $phoneLabel }}</a></div>
        </li>
        <li class="flex gap-3">
          <i class="fa-solid fa-envelope mt-1 w-5 text-brand-orange" aria-hidden="true"></i>
          <div><p class="font-semibold text-slate-900">Email</p><a href="mailto:egtechsolution1@gmail.com" class="text-slate-600 hover:text-brand-navy">egtechsolution1@gmail.com</a></div>
        </li>
        <li class="flex flex-wrap gap-3 pt-2">
          <a href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 rounded-xl bg-brand-navy px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy-dark">
            <i class="fa-solid fa-diamond-turn-right" aria-hidden="true"></i> Get directions
          </a>
          <a href="{{ $whatsapp }}" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-50">
            <i class="fa-brands fa-whatsapp text-emerald-600" aria-hidden="true"></i> WhatsApp us
          </a>
        </li>
      </ul>
      <iframe title="Map to EG Tech Solution, Dhangadhi" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
              src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed"
              class="h-72 w-full flex-1 rounded-2xl border border-slate-200 lg:h-auto lg:min-h-72"></iframe>
    </div>
  </div>
</section>

{{-- Floating WhatsApp button (above the mobile bottom bar) --}}
<a href="{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="Chat with EG Tech Solution on WhatsApp"
   class="fixed bottom-20 right-4 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-3xl text-white shadow-xl transition hover:scale-105 hover:bg-emerald-600 sm:bottom-6">
  <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
</a>

<script>
  (function () {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Count numbers up from 0 when the stats come into view
    function countUp(el) {
      const target = Number(el.dataset.countTo);
      if (reduce || !target) return;
      const start = performance.now(), dur = 1400;
      const step = (now) => {
        const p = Math.min((now - start) / dur, 1);
        el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString();
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    }

    if (!('IntersectionObserver' in window)) {
      document.querySelectorAll('.reveal').forEach(el => el.classList.add('is-visible'));
      return;
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach((e) => {
        if (!e.isIntersecting) return;
        e.target.classList.add('is-visible');
        e.target.querySelectorAll('[data-count-to]').forEach(countUp);
        io.unobserve(e.target);
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
  })();
</script>
@endsection
