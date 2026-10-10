@extends('frontend.layouts.app')

@push('head')
    <title>EG Tech Solution</title>
@endpush

@section('title', $meta_title)
@section('meta_description', $meta_description)
@section('meta_keywords', $meta_keywords)

@section('content')
  <style>
      /* ================== Banner polish ================== */
      #hero [data-carousel-item].active img {
        animation: kenburns 12s ease-in-out both;
      }
      @keyframes kenburns { 0%{transform:scale(1)} 100%{transform:scale(1.06)} }

      .hero-dot { opacity:.5; transform:scale(.9); transition:opacity .3s, transform .3s; }
      .hero-dot.active { opacity:1; transform:scale(1.2) }

      /* ================== CELEBRATION ================== */
      /* Mount this over each slide; JS will populate children */
      .celebration {
        position:absolute; inset:0; pointer-events:none; overflow:hidden;
        /* center origin (overridable via inline style: --cx/--cy) */
        --cx: 50%; --cy: 50%;
      }
      .piece {
        position:absolute; left:var(--cx); top:var(--cy);
        transform: translate(-50%, -50%) rotate(var(--ang,0deg)) translateX(0) scale(var(--scale,1));
        opacity:0;
        animation: pieceFlight var(--dur,6s) var(--delay,0s) cubic-bezier(.22,.61,.36,1) forwards;
        will-change: transform, opacity, filter;
      }
      /* Ribbon (gift ribbon strip with gradient) */
      .piece--ribbon {
        width: 9px; height: 48px; border-radius: 6px;
        background: linear-gradient(180deg,
          hsl(var(--h), 95%, 60%) 0%,
          hsl(calc(var(--h) + 25), 85%, 55%) 50%,
          hsl(var(--h), 95%, 60%) 100%);
        box-shadow: 0 6px 18px rgba(0,0,0,.18);
        filter: saturate(115%);
      }
      /* Star (CSS star via clip-path) */
      .piece--star {
        width: 18px; height:18px;
        background: radial-gradient(circle at 30% 30%, #fff 0 20%, transparent 21%),
                    hsl(var(--h), 90%, 60%);
        clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%,
                           79% 91%, 50% 70%, 21% 91%, 32% 57%,
                           2% 35%, 39% 35%);
        box-shadow: 0 0 12px rgba(255,255,255,.8);
      }
      /* Confetti square */
      .piece--confetti {
        width: 12px; height:12px; border-radius:2px;
        background: hsl(var(--h), 90%, 55%);
        box-shadow: 0 0 10px rgba(0,0,0,.15);
      }
      /* Spark dot */
      .piece--dot {
        width: 8px; height:8px; border-radius:9999px;
        background: #fff;
        box-shadow: 0 0 10px rgba(255,255,255,.9), 0 0 20px rgba(255,255,255,.6);
      }

      /* Flight path: shoot out, then drift and fade */
      @keyframes pieceFlight {
        /* start invisible at center */
        0% {
          opacity: 0;
          transform: translate(-50%, -50%) rotate(var(--ang)) translateX(0) scale(var(--scale,1));
          filter: blur(0);
        }
        /* pop visible quickly */
        8% {
          opacity: 1;
          transform: translate(-50%, -50%) rotate(var(--ang)) translateX(20px) scale(calc(var(--scale,1) * 1.03));
        }
        /* shoot to full radial distance */
        35% {
          transform: translate(-50%, -50%) rotate(var(--ang)) translateX(var(--dist)) rotate(var(--twist, 0deg))
                     translateY(0) scale(calc(var(--scale,1) * 1.02));
          filter: blur(.2px);
        }
        /* drift with gravity-ish pull and spin */
        100% {
          opacity: 0;
          transform:
            translate(-50%, -50%)
            rotate(var(--ang))
            translateX(calc(var(--dist) + var(--driftX)))
            translateY(var(--driftY))
            rotate(var(--spin))
            scale(var(--scale,1));
          filter: blur(.5px);
        }
      }

      /* ================== Drawer/Button ================== */
      #openFilterBtn { transition: opacity .25s, transform .25s; }
      #filterDrawer  { transition: transform .3s ease; }
    </style>

  <!-- Fixed Filter button (mobile) -->
  <button id="openFilterBtn"
          class="fixed top-[100px] left-0 z-[80] inline-flex items-center gap-2 rounded-r-xl bg-slate-900 px-4 py-2 text-white text-sm font-semibold shadow hover:bg-slate-800 lg:hidden">
      &#9881; Filter
  </button>

  <!-- Overlay (mobile only) -->
  <div id="filterOverlay" class="fixed inset-0 z-[60] bg-black/40 opacity-0 pointer-events-none transition lg:hidden"></div>

  {{-- Main --}}
  <section class="w-full px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-10 gap-6">

      {{-- ===== Filters (drawer on mobile, sidebar on lg+) ===== --}}
      <aside id="filterDrawer"
             class="fixed inset-y-0 left-0 z-[70] w-80 max-w-[85%] -translate-x-full bg-white shadow-2xl
                    lg:relative lg:inset-auto lg:z-auto lg:w-auto lg:translate-x-0 lg:shadow-none lg:col-span-2">

        <div class="lg:hidden sticky top-0 z-[71] flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3">
          <h3 class="text-sm font-semibold text-slate-900">Filters</h3>
          <button id="closeFilterBtn"
                  class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-700">
            Close ✕
          </button>
        </div>

        <form id="filterForm" action="{{ url()->current() }}" method="GET"
              class="space-y-6 p-4 lg:rounded-2xl lg:border lg:border-slate-200 lg:bg-white lg:p-4 lg:shadow-sm">

          {{-- Price Filter --}}
          <div>
            <h4 class="mb-2 text-sm font-semibold text-slate-900">Price</h4>
            <div class="flex items-center justify-between text-xs text-slate-600">
              <span>0</span>
              <span>Max: <b id="priceValue">{{ number_format($maxPrice) }}</b></span>
            </div>
            <input type="range" name="max_price" id="max_price" min="0" max="{{ $maxFromDb }}"
                   value="{{ $maxPrice }}" class="mt-2 w-full accent-red-600"
                   oninput="document.getElementById('priceValue').textContent = Number(this.value).toLocaleString()"
                   onchange="document.getElementById('filterForm').submit()" />
          </div>

          {{-- Category Filter --}}
          <div>
            <h4 class="mb-2 text-sm font-semibold text-slate-900">Category</h4>
            <ul class="max-h-60 space-y-2 overflow-y-auto pr-1">
              @foreach ($categories as $cat)
                <li>
                  <label class="flex cursor-pointer items-center gap-2 text-sm">
                    <input type="checkbox" name="category[]" value="{{ $cat->id }}"
                           class="h-4 w-4 rounded border-slate-300 text-red-600 focus:ring-red-500"
                           {{ in_array($cat->id, $selectedCategoryIds ?? []) ? 'checked' : '' }}
                           onchange="document.getElementById('filterForm').submit()">
                    <span class="flex-1 text-slate-700">
                      {{ $cat->name }}
                      <span class="ml-1 text-xs text-slate-500">({{ $cat->products_count }})</span>
                    </span>
                  </label>
                </li>
              @endforeach
            </ul>
          </div>

          {{-- Brand Filter --}}
          <div>
            <h4 class="mb-2 text-sm font-semibold text-slate-900">Brand</h4>
            <ul class="max-h-60 space-y-2 overflow-y-auto pr-1">
              @foreach ($brands as $brand)
                <li>
                  <label class="flex cursor-pointer items-center gap-2 text-sm">
                    <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                           class="h-4 w-4 rounded border-slate-300 text-red-600 focus:ring-red-500"
                           {{ in_array($brand->id, $selectedBrandIds ?? []) ? 'checked' : '' }}
                           onchange="document.getElementById('filterForm').submit()">
                    <span class="flex-1 text-slate-700">
                      {{ $brand->name }}
                      <span class="ml-1 text-xs text-slate-500">({{ $brand->products_count }})</span>
                    </span>
                  </label>
                </li>
              @endforeach
            </ul>
          </div>

          {{-- Clear Filters --}}
          @if (request()->has('category') || request()->has('brand') || request()->has('max_price') || request()->filled('search'))
            <a href="{{ url()->current() }}"
               class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200">
              Clear all filters
            </a>
          @endif
        </form>
      </aside>

      {{-- ===== Banners (8/10 on lg+) ===== --}}
      <div class="lg:col-span-8">
        <section class="relative w-full">
          <div id="hero" class="relative overflow-hidden ring-1 ring-slate-200 bg-white "
               data-carousel="slide" data-autoplay="true" data-interval="4000">
            <div class="relative">

              @forelse($banners as $i => $b)
                {{-- Make first slide visible & active to avoid hidden banner --}}
                <div class="{{ $i === 0 ? 'block' : 'hidden' }} duration-700 ease-in-out"
                     data-carousel-item="{{ $i === 0 ? 'active' : '' }}">
                  <img src="{{ asset($b->image_path) }}" alt="{{ $b->alt_text ?? $b->name }}"
                       class="w-full h-56 sm:h-72 md:h-[60vh] lg:h-[65vh] object-cover"
                       loading="{{ $i === 0 ? 'eager' : 'lazy' }}" />

                  {{-- Celebration layer (center origin) --}}
                  <div class="celebration" data-celebrate style="--cx:50%; --cy:50%;"></div>

                  {{-- CTA --}}
                  <div class="absolute top-4 left-4 sm:top-8 sm:left-8 z-10 max-w-xl text-[rgb(0,46,91)] bg-white/70 p-4 rounded-lg">
                    <h2 class="text-lg sm:text-2xl lg:text-4xl font-extrabold leading-tight drop-shadow">
                      {{ $b->name }}
                    </h2>
                    @if (!empty($b->link_url))
                      <a href="{{ $b->link_url }}"
                         class="mt-3 inline-flex items-center rounded-xl bg-emerald-500 px-3 py-2 text-xs sm:text-sm font-semibold text-white shadow-lg hover:bg-emerald-600">
                        Shop Now →
                      </a>
                    @endif
                  </div>

                  {{-- Whole slide clickable if link exists --}}
                  @if ($b->link_url)
                    <a href="{{ $b->link_url }}" class="absolute inset-0" aria-label="{{ $b->name }}"></a>
                  @endif
                </div>
              @empty
                <div class="p-16 text-center text-slate-500">No banners yet.</div>
              @endforelse
            </div>

            @if (($banners ?? collect())->count() > 1)
              <div class="absolute bottom-3 left-1/2 z-20 -translate-x-1/2 flex gap-2">
                @foreach ($banners as $i => $b)
                  <button type="button" class="hero-dot h-2 w-2 rounded-full bg-white/80"
                          data-carousel-slide-to="{{ $i }}"></button>
                @endforeach
              </div>
            @endif

            <button type="button" class="absolute top-0 left-0 z-20 flex items-center justify-center h-full px-3"
                    data-carousel-prev>
              <span class="rounded-full bg-white/30 p-2 hover:bg-white/60">‹</span>
            </button>
            <button type="button" class="absolute top-0 right-0 z-20 flex items-center justify-center h-full px-3"
                    data-carousel-next>
              <span class="rounded-full bg-white/30 p-2 hover:bg-white/60">›</span>
            </button>
          </div>
        </section>
      </div>

    </div>
  </section>

  {{-- Featured Products --}}
  <section class=" bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">🔥 Featured Products</h2>
        <p class="mt-2 text-gray-600">Hand-picked items just for you</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse($featuredProducts as $product)
          <div class="group relative bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition overflow-hidden">
            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100 relative">
              <img src="{{ asset($product->image) }}" alt="{{ $product->image }}"
                   class="h-40 sm:h-48 md:h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105">
              @if ($product->sale_price && $product->sale_price < $product->price)
                <div class="absolute top-3 left-3">
                  <span class="inline-flex items-center rounded-md bg-red-500 px-2 py-1 text-xs font-semibold text-white shadow">
                    -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100) }}%
                  </span>
                </div>
              @endif
            </div>
            <div class="p-3 sm:p-4">
              <h3 class="text-xs sm:text-sm font-semibold text-gray-900 group-hover:text-indigo-600 truncate">
                <a href="{{ url('/products/' . $product->slug . '/' . $product->id) }}">{{ $product->name }}</a>
              </h3>
              <div class="mt-1 flex items-center gap-2">
                <span class="text-sm sm:text-base font-bold text-indigo-600">
                  Rs. {{ number_format($product->final_price, 0) }}
                </span>
                @if ($product->sale_price && $product->sale_price < $product->price)
                  <span class="text-[10px] sm:text-sm line-through text-gray-400">
                    Rs. {{ number_format($product->price, 0) }}
                  </span>
                @endif
              </div>
              <p class="mt-1 text-[10px] sm:text-xs text-gray-500">
                {{ $product->stock_status === 'in_stock' ? 'In stock' : 'Out of stock' }}
              </p>
              <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-2 py-1.5 sm:px-3 sm:py-2 text-xs sm:text-sm font-semibold text-white shadow hover:bg-indigo-700">
                  Add to Cart
                </button>
              </form>
            </div>
          </div>
        @empty
          <p class="col-span-4 text-center text-gray-500">No featured products available right now.</p>
        @endforelse
      </div>
    </div>
  </section>

  {{-- Other Products --}}
  <section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Other Products</h2>
        <p class="mt-2 text-gray-600">Deal just for you</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse($Randproducts as $product)
          <div class="group relative bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition overflow-hidden">
            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100 relative">
              <img src="{{ asset($product->image) }}" alt="{{ $product->image }}"
                   class="h-40 sm:h-48 md:h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105">
              @if ($product->sale_price && $product->sale_price < $product->price)
                <div class="absolute top-3 left-3">
                  <span class="inline-flex items-center rounded-md bg-red-500 px-2 py-1 text-xs font-semibold text-white shadow">
                    -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100) }}%
                  </span>
                </div>
              @endif
            </div>
            <div class="p-3 sm:p-4">
              <h3 class="text-xs sm:text-sm font-semibold text-gray-900 group-hover:text-indigo-600 truncate">
                <a href="{{ url('/products/' . $product->slug . '/' . $product->id) }}">{{ $product->name }}</a>
              </h3>
              <div class="mt-1 flex items-center gap-2">
                <span class="text-sm sm:text-base font-bold text-indigo-600">
                  Rs. {{ number_format($product->final_price, 0) }}
                </span>
                @if ($product->sale_price && $product->sale_price < $product->price)
                  <span class="text-[10px] sm:text-sm line-through text-gray-400">
                    Rs. {{ number_format($product->price, 0) }}
                  </span>
                @endif
              </div>
              <p class="mt-1 text-[10px] sm:text-xs text-gray-500">
                {{ $product->stock_status === 'in_stock' ? 'In stock' : 'Out of stock' }}
              </p>
              <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-2 py-1.5 sm:px-3 sm:py-2 text-xs sm:text-sm font-semibold text-white shadow hover:bg-indigo-700">
                  Add to Cart
                </button>
              </form>
            </div>
          </div>
        @empty
          <p class="col-span-4 text-center text-gray-500">No featured products available right now.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- Scripts -->
  <script>
    /* ===== Drawer (hide filter button while open) ===== */
    (function () {
      const drawer  = document.getElementById('filterDrawer');
      const overlay = document.getElementById('filterOverlay');
      const openBtn = document.getElementById('openFilterBtn');
      const closeBtn= document.getElementById('closeFilterBtn');

      function openDrawer() {
        drawer.classList.remove('-translate-x-full');
        overlay.classList.add('opacity-100','pointer-events-auto');
        openBtn.classList.add('hidden');
        document.documentElement.classList.add('overflow-hidden');
        document.body.classList.add('overflow-hidden');
      }
      function closeDrawer() {
        drawer.classList.add('-translate-x-full');
        overlay.classList.remove('opacity-100','pointer-events-auto');
        openBtn.classList.remove('hidden');
        document.documentElement.classList.remove('overflow-hidden');
        document.body.classList.remove('overflow-hidden');
      }

      openBtn?.addEventListener('click', openDrawer);
      closeBtn?.addEventListener('click', closeDrawer);
      overlay?.addEventListener('click', closeDrawer);
      window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDrawer(); });

      const mq = window.matchMedia('(min-width: 1024px)');
      mq.addEventListener?.('change', (e) => { if (e.matches) closeDrawer(); });
    })();

    /* ===== Celebration builder (ribbons, stars, confetti, dots) ===== */
    function buildCelebration(container, count = 48) {
      container.innerHTML = '';
      const palette = [0, 25, 45, 195, 265, 305]; // hues (HSL)
      for (let i = 0; i < count; i++) {
        const el = document.createElement('span');
        el.classList.add('piece');

        // type mix: ribbons, stars, confetti, dots
        const r = Math.random();
        if (r < 0.30) el.classList.add('piece--ribbon');
        else if (r < 0.55) el.classList.add('piece--star');
        else if (r < 0.85) el.classList.add('piece--confetti');
        else el.classList.add('piece--dot');

        // random variables
        const hue   = palette[i % palette.length] + Math.floor(Math.random()*20 - 10);
        const angle = Math.random()*360 + 'deg';
        const dist  = (30 + Math.random()*45) + 'vmin';   // how far the burst reaches
        const driftX= (Math.random()*30 - 15) + 'vmin';   // slight lateral drift
        const driftY= (20 + Math.random()*35) + 'vmin';   // gravity pull
        const spin  = (Math.random()*720 - 360) + 'deg';
        const twist = (Math.random()*60 - 30) + 'deg';
        const delay = (Math.random()*3) + 's';            // spread over 3s
        const dur   = (5.5 + Math.random()*2) + 's';
        const scale = (0.8 + Math.random()*0.7).toFixed(2);

        el.style.setProperty('--h', hue);
        el.style.setProperty('--ang', angle);
        el.style.setProperty('--dist', dist);
        el.style.setProperty('--driftX', driftX);
        el.style.setProperty('--driftY', driftY);
        el.style.setProperty('--spin',  spin);
        el.style.setProperty('--twist', twist);
        el.style.setProperty('--delay', delay);
        el.style.setProperty('--dur',   dur);
        el.style.setProperty('--scale', scale);

        container.appendChild(el);
      }
    }

    // Initialize celebration on each slide
    document.querySelectorAll('[data-celebrate]').forEach(cel => buildCelebration(cel));

    /* ===== Mark active slide & trigger celebration ===== */
    (function(){
      const hero = document.getElementById('hero');
      if (!hero) return;
      const items = hero.querySelectorAll('[data-carousel-item]');
      const dots  = hero.querySelectorAll('[data-carousel-slide-to]');
      
      // Store interval IDs for each slide
      const celebrationIntervals = new Map();
      
      const markActive = () => {
        items.forEach((el, i) => {
          const visible = !el.classList.contains('hidden') || el.getAttribute('data-carousel-item') === 'active';
          const wasActive = el.classList.contains('active');
          
          el.classList.toggle('active', visible);
          dots[i]?.classList.toggle('active', visible);
          
          const celebrationContainer = el.querySelector('[data-celebrate]');
          
          // Slide became active
          if (visible && !wasActive && celebrationContainer) {
            // Clear any existing interval for this slide
            if (celebrationIntervals.has(i)) {
              clearInterval(celebrationIntervals.get(i));
            }
            
            // Trigger first celebration immediately
            buildCelebration(celebrationContainer);
            
            // Set up recurring celebration every 10 seconds
            const intervalId = setInterval(() => {
              buildCelebration(celebrationContainer);
            }, 10000);
            
            celebrationIntervals.set(i, intervalId);
          }
          
          // Slide became inactive - clear its interval
          if (!visible && wasActive && celebrationIntervals.has(i)) {
            clearInterval(celebrationIntervals.get(i));
            celebrationIntervals.delete(i);
          }
        });
      };
      
      markActive();
      const obs = new MutationObserver(markActive);
      items.forEach(it => obs.observe(it, { attributes:true, attributeFilter:['class','data-carousel-item'] }));
    })();
  </script>
@endsection