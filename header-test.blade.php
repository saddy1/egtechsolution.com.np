{{-- resources/views/home.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'EG Tech — Electronics & Gadgets')

@section('content')
<!-- Top bar -->
<div class="bg-slate-900 text-slate-100">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between h-10 text-xs">
    <div class="hidden sm:flex items-center gap-4">
      <span>Welcome to EG Tech</span>
      <span class="hidden md:inline text-slate-400">Hotline: +977-9800000000</span>
    </div>
    <div class="flex items-center gap-4">
      <a href="" class="hover:text-white">Help</a>
      <a href="" class="hover:text-white">Track Order</a>
      @auth
        <a href="" class="hover:text-white">My Account</a>
      @else
        <a href="" class="hover:text-white">Login</a>
        <a href="" class="hover:text-white">Register</a>
      @endauth
    </div>
  </div>
</div>

<!-- Header -->
<header class="border-b bg-white">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
    <div class="flex items-center gap-4">
      <!-- Logo -->
      <a href="{{ url('/') }}" class="flex items-center gap-2">
        <img src="{{ asset('assets/eg_tech_logo_white.png') }}" class="h-8 w-auto" alt="EG Tech">
        <span class="hidden sm:block text-lg font-semibold tracking-tight">EG TECH</span>
      </a>

      <!-- Category button (mobile) -->
      <button class="lg:hidden ml-auto inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-sm"
              onclick="document.getElementById('mobile-nav').classList.toggle('hidden')">
        ☰ Categories
      </button>

      <!-- Search -->
      <form class="flex-1 hidden lg:flex">
        <div class="relative w-full">
          <input type="search" name="q" placeholder="Search for products, brands and more"
                 class="w-full rounded-l-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
          <select name="cat"
                  class="absolute top-0 right-28 h-full hidden md:block border-y border-l border-slate-300 bg-white px-3 text-sm rounded-none">
            <option value="">All Categories</option>
            @foreach($categories ?? [] as $c)
              <option>{{ $c->name ?? $c }}</option>
            @endforeach
          </select>
          <button class="absolute top-0 right-0 h-full rounded-r-xl bg-indigo-600 px-4 text-white font-semibold hover:bg-indigo-700">
            Search
          </button>
        </div>
      </form>

      <!-- Icons -->
      <div class="ml-auto flex items-center gap-3">
        <a href="" class="rounded-xl border px-3 py-2 hover:bg-slate-50">♡</a>
        <a href="" class="rounded-xl border px-3 py-2 hover:bg-slate-50">🛒
          <span class="ml-1 text-xs text-slate-500">{{ $cartCount ?? 0 }}</span>
        </a>
      </div>
    </div>

    <!-- Mobile categories dropdown -->
    <nav id="mobile-nav" class="mt-3 hidden lg:hidden">
      <div class="grid grid-cols-2 gap-2 text-sm">
        @foreach(($categories ?? ['Laptops','Mobiles','TVs','Audio','Gaming','Appliances','Accessories','Networking']) as $c)
          <a href=""
             class="rounded-lg border px-3 py-2 hover:bg-slate-50">{{ is_string($c)?$c:($c->name ?? '') }}</a>
        @endforeach
      </div>
    </nav>
  </div>
</header>

<!-- Categories strip (desktop) -->
<div class="hidden border-b bg-white lg:block">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <ul class="flex items-center gap-6 text-sm h-12">
      @foreach(($categories ?? ['Laptops','Mobiles','TVs','Audio','Gaming','Appliances','Accessories','Networking','Cameras']) as $c)
        <li>
          <a href=""
             class="text-slate-700 hover:text-indigo-600">{{ is_string($c)?$c:($c->name ?? '') }}</a>
        </li>
      @endforeach
    </ul>
  </div>
</div>

<!-- Hero slider -->
<section class="relative">
  <div id="hero" class="relative h-64 sm:h-80 md:h-[70vh] lg:h-[70vh] overflow-hidden" data-carousel="slide" data-autoplay="true" data-interval="6000">
    @php
      $slides = [
        ['bg'=>asset('assets/banners/banner1.jpg'),'headline'=>'Mega Sale on Laptops','sub'=>'Save up to 35% on top brands','cta'=>route('shop')],
        ['bg'=>asset('assets/banners/banner2.jpg'),'headline'=>'Smartphones Festival','sub'=>'Latest 5G devices at best prices','cta'=>route('shop')],
        ['bg'=>asset('assets/banners/banner3.jpg'),'headline'=>'Home Entertainment','sub'=>'4K TVs & Soundbars combos','cta'=>route('shop')],
      ];
    @endphp

    <div class="relative h-full">
      @foreach($slides as $i=>$s)
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
          <img src="{{ $s['bg'] }}" class="absolute block h-full w-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" alt="Slide {{ $i+1 }}">
          <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>

          <div class="relative z-10 mx-auto max-w-7xl h-full px-4 sm:px-6 lg:px-8 flex items-center">
            <div class="max-w-xl text-white">
              <h2 class="text-3xl sm:text-5xl font-extrabold leading-tight">{{ $s['headline'] }}</h2>
              <p class="mt-3 text-sm sm:text-base text-slate-200">{{ $s['sub'] }}</p>
              <a href="{{ $s['cta'] }}"
                 class="mt-6 inline-flex items-center rounded-xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg hover:bg-emerald-600">
                Shop Now →
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Dots -->
    <div class="absolute bottom-4 left-1/2 z-20 -translate-x-1/2 flex gap-2">
      @foreach($slides as $i=>$s)
        <button type="button" class="h-2.5 w-2.5 rounded-full bg-white/40" data-carousel-slide-to="{{ $i }}"></button>
      @endforeach
    </div>

    <!-- Controls -->
    <button type="button" class="absolute top-0 left-0 z-20 flex h-full items-center px-3" data-carousel-prev>
      <span class="rounded-full bg-white/30 p-2 hover:bg-white/60">‹</span>
    </button>
    <button type="button" class="absolute top-0 right-0 z-20 flex h-full items-center px-3" data-carousel-next>
      <span class="rounded-full bg-white/30 p-2 hover:bg-white/60">›</span>
    </button>
  </div>
</section>

<!-- Promo banners -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <a class="relative overflow-hidden rounded-2xl ring-1 ring-slate-200 bg-white group">
      <img src="{{ asset('assets/promos/promo1.jpg') }}" class="h-40 w-full object-cover group-hover:scale-105 transition" alt="">
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
      <div class="absolute bottom-3 left-4 text-white">
        <p class="text-sm">Weekend Deals</p>
        <h3 class="text-lg font-bold">Headphones & Audio</h3>
      </div>
    </a>
    <a class="relative overflow-hidden rounded-2xl ring-1 ring-slate-200 bg-white group">
      <img src="{{ asset('assets/promos/promo2.jpg') }}" class="h-40 w-full object-cover group-hover:scale-105 transition" alt="">
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
      <div class="absolute bottom-3 left-4 text-white">
        <p class="text-sm">Top Picks</p>
        <h3 class="text-lg font-bold">Gaming Essentials</h3>
      </div>
    </a>
    <a class="relative overflow-hidden rounded-2xl ring-1 ring-slate-200 bg-white group">
      <img src="{{ asset('assets/promos/promo3.jpg') }}" class="h-40 w-full object-cover group-hover:scale-105 transition" alt="">
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
      <div class="absolute bottom-3 left-4 text-white">
        <p class="text-sm">Limitless Work</p>
        <h3 class="text-lg font-bold">Laptops & Monitors</h3>
      </div>
    </a>
  </div>
</section>

<!-- Deals of the day -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
  <div class="mb-4 flex items-center justify-between">
    <h3 class="text-xl font-bold">Deals of the Day</h3>
    <a href="" class="text-sm text-indigo-600 hover:underline">View All</a>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
    @foreach(($deals ?? []) as $p)
      <a href="{{ route('product.show', $p->id) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white p-3 hover:shadow-md transition">
        <div class="relative">
          <img src="{{ asset('assets/product/'.$p->name.'.png') }}" class="h-36 w-full object-contain" alt="{{ $p->name }}">
          <span class="absolute top-2 left-2 rounded-md bg-rose-500 px-2 py-0.5 text-xs font-bold text-white">-{{ (int)round(($p->discount/$p->rate)*100) }}%</span>
        </div>
        <div class="mt-3">
          <p class="line-clamp-2 text-sm font-medium text-slate-900">{{ $p->name }}</p>
          <div class="mt-1 flex items-center gap-2">
            <span class="text-indigo-600 font-bold">Rs.{{ number_format($p->rate - $p->discount) }}</span>
            <span class="text-xs text-slate-400 line-through">Rs.{{ number_format($p->rate) }}</span>
          </div>
        </div>
      </a>
    @endforeach
    @if(empty($deals) || count($deals)===0)
      @for($i=0;$i<5;$i++)
        <div class="rounded-2xl border border-dashed p-6 text-center text-slate-400">Coming soon</div>
      @endfor
    @endif
  </div>
</section>

<!-- Featured products grid -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-4 flex items-center justify-between">
    <h3 class="text-xl font-bold">Featured Products</h3>
    <a href="{{ route('shop') }}" class="text-sm text-indigo-600 hover:underline">Browse All</a>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    @foreach(($featured ?? $products ?? []) as $p)
      <a href="{{ route('product.show', $p->id) }}" class="group rounded-2xl border border-slate-200 bg-white p-3 hover:shadow-md transition">
        <img src="{{ asset('assets/product/'.$p->name.'.png') }}" class="h-36 w-full object-contain" alt="{{ $p->name }}">
        <div class="mt-3">
          <p class="line-clamp-2 text-sm font-medium text-slate-900">{{ $p->name }}</p>
          <p class="mt-1 text-xs text-slate-500">{{ $p->brand }}</p>
          <div class="mt-2 flex items-center gap-2">
            <span class="text-indigo-600 font-bold">Rs.{{ number_format($p->rate) }}</span>
          </div>
        </div>
        <div class="mt-3">
          <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $p->id }}">
            <input type="hidden" name="quantity" value="1">
            <button class="w-full rounded-xl bg-emerald-500 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
              Add to Cart
            </button>
          </form>
        </div>
      </a>
    @endforeach
  </div>
</section>

<!-- Newsletter -->
<section class="bg-indigo-50">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200 md:flex md:items-center md:justify-between">
      <div>
        <h3 class="text-xl font-bold text-slate-900">Get updates & exclusive offers</h3>
        <p class="text-sm text-slate-600">Sign up for our newsletter</p>
      </div>
      <form class="mt-4 md:mt-0 flex w-full md:w-auto">
        <input type="email" placeholder="you@example.com" class="w-full md:w-96 rounded-l-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
        <button class="rounded-r-xl bg-indigo-600 px-4 py-2.5 text-white font-semibold hover:bg-indigo-700">Subscribe</button>
      </form>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-slate-900 text-slate-200">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
    <div class="col-span-2">
      <div class="flex items-center gap-2">
        <img src="{{ asset('assets/eg_tech_logo_white.png') }}" class="h-8" alt="">
        <span class="font-semibold">EG TECH</span>
      </div>
      <p class="mt-3 text-sm text-slate-400">Electronics, gadgets & support—delivered fast.</p>
    </div>
    <div>
      <h4 class="font-semibold mb-2">Shop</h4>
      <ul class="space-y-1 text-sm text-slate-300">
        <li><a href="" class="hover:text-white">All Products</a></li>
        <li><a href="" class="hover:text-white">Deals</a></li>
        <li><a href="{" class="hover:text-white">New Arrivals</a></li>
      </ul>
    </div>
    <div>
      <h4 class="font-semibold mb-2">Support</h4>
      <ul class="space-y-1 text-sm text-slate-300">
        <li><a href="" class="hover:text-white">Help Center</a></li>
        <li><a href="" class="hover:text-white">Returns</a></li>
        <li><a href="" class="hover:text-white">Warranty</a></li>
      </ul>
    </div>
    <div>
      <h4 class="font-semibold mb-2">Company</h4>
      <ul class="space-y-1 text-sm text-slate-300">
        <li><a href="" class="hover:text-white">About</a></li>
        <li><a href="" class="hover:text-white">Contact</a></li>
        <li><a href="" class="hover:text-white">Careers</a></li>
      </ul>
    </div>
  </div>
  <div class="border-t border-slate-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 text-xs text-slate-400 flex flex-col sm:flex-row gap-2 justify-between">
      <span>© {{ date('Y') }} EG Tech. All rights reserved.</span>
      <div class="flex items-center gap-3">
        <span>Visa</span><span>Mastercard</span><span>Cash on Delivery</span>
      </div>
    </div>
  </div>
</footer>

{{-- Minimal carousel JS (no external lib) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-carousel="slide"]').forEach((root) => {
    const items = Array.from(root.querySelectorAll('[data-carousel-item]'));
    const dots  = Array.from(root.querySelectorAll('[data-carousel-slide-to]'));
    const prev  = root.querySelector('[data-carousel-prev]');
    const next  = root.querySelector('[data-carousel-next]');
    const autoplay = root.getAttribute('data-autoplay') !== 'false';
    const intervalMs = parseInt(root.getAttribute('data-interval') || '6000', 10);

    if (!items.length) return;
    let index = 0;
    function show(i){
      index = (i + items.length) % items.length;
      items.forEach((el,k)=> el.classList.toggle('hidden', k!==index));
      dots.forEach((d,k)=> d.classList.toggle('bg-white/80', k===index));
    }
    function nextSlide(){ show(index+1); }
    function prevSlide(){ show(index-1); }

    let timer=null; function start(){ if(autoplay){ timer=setInterval(nextSlide, intervalMs)} }
    function stop(){ if(timer){clearInterval(timer); timer=null;} }

    dots.forEach((b,k)=> b.addEventListener('click', ()=> show(k)));
    prev?.addEventListener('click', prevSlide);
    next?.addEventListener('click', nextSlide);
    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    document.addEventListener('visibilitychange', ()=> document.hidden?stop():start());

    show(0); start();
  });
});
</script>
@endsection
