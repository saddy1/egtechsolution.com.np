@extends('frontend.layouts.app')
@push('head')
    <title>EG Tech Solution</title>
@endpush

@section('content')


    {{-- Banner Section Start --}}

    {{-- <div id="banner-carousel" class="relative w-full" data-carousel="slide" data-autoplay="true" data-interval="6000">
        <!-- Carousel wrapper -->
        <div class="relative h-64 sm:h-80 md:h-[80vh] lg:h-[100vh] overflow-hidden">
            <!-- Item 1 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets/banner5.jpg') }}"
                    class="absolute block w-full h-full object-contain md:object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black"
                    alt="Banner 1">
            </div>
            <!-- Item 2 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets/banner2.jpg') }}"
                    class="absolute block w-full h-full object-contain md:object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black"
                    alt="Banner 2">
            </div>
            <!-- Item 3 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets/banner3.jpg') }}"
                    class="absolute block w-full h-full object-contain md:object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black"
                    alt="Banner 3">
            </div>
        </div>

        <!-- Indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 bottom-4 left-1/2 space-x-2">
            <button type="button" class="w-3 h-3 rounded-full bg-white/50" aria-current="true" aria-label="Slide 1"
                data-carousel-slide-to="0"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 2"
                data-carousel-slide-to="1"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 3"
                data-carousel-slide-to="2"></button>
        </div>

        <!-- Controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white">
                <svg class="w-4 h-4 text-white" viewBox="0 0 6 10" fill="none">
                    <path d="M5 1 1 5l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white">
                <svg class="w-4 h-4 text-white" viewBox="0 0 6 10" fill="none">
                    <path d="m1 9 4-4-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div> --}}



<section class="relative w-full">
  <div id="hero" class="relative overflow-hidden" data-carousel="slide" data-autoplay="true" data-interval="6000">
    <div class="relative">
      @forelse($banners as $i => $b)
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
          <img
            src="{{ Storage::url($b->image_path) }}"
            alt="{{ $b->alt_text ?? $b->name }}"
            class="w-full h-auto object-contain md:object-cover"
            loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
          />
          <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/30 to-transparent"></div>

          {{-- Optional: clickable banner --}}
          @if($b->link_url)
            <a href="{{ $b->link_url }}" class="absolute inset-0" aria-label="{{ $b->name }}"></a>
          @endif

          {{-- Top-left text/CTA - optional if you store meta for banners --}}
          <div class="absolute top-6 left-6 sm:top-12 sm:left-12 z-10 max-w-xl text-white">
              <h2 class="text-xl sm:text-3xl lg:text-5xl font-extrabold leading-tight">{{ $b->name }}</h2>
              <a href="{{ $b->link_url ?? '#' }}" class="mt-4 inline-flex items-center rounded-xl bg-emerald-500 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-lg hover:bg-emerald-600">Shop Now →</a>
          </div>
        </div>
      @empty
        {{-- Fallback if no banners --}}
        <div class="p-16 text-center text-slate-500">No banners yet.</div>
      @endforelse
    </div>

    {{-- Dots --}}
    @if(($banners ?? collect())->count() > 1)
      <div class="absolute bottom-3 left-1/2 z-20 -translate-x-1/2 flex gap-2">
        @foreach($banners as $i => $b)
          <button type="button" class="h-2 w-2 rounded-full bg-white/40" data-carousel-slide-to="{{ $i }}"></button>
        @endforeach
      </div>
    @endif

    {{-- Controls --}}
    <button type="button" class="absolute top-0 left-0 z-20 flex items-center justify-center h-full px-3" data-carousel-prev>
      <span class="rounded-full bg-white/30 p-2 hover:bg-white/60">‹</span>
    </button>
    <button type="button" class="absolute top-0 right-0 z-20 flex items-center justify-center h-full px-3" data-carousel-next>
      <span class="rounded-full bg-white/30 p-2 hover:bg-white/60">›</span>
    </button>
  </div>
</section>






    {{-- Banner Section End --> --}}

    
<!-- Promo banners -->
<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <a class="relative overflow-hidden rounded-2xl ring-1 ring-slate-200 bg-white group">
      <img src="{{ asset('assets/headphone.jpg') }}" class="h-80 w-full object-cover group-hover:scale-105 transition" alt="">
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
      <div class="absolute bottom-3 left-4 text-white">
        <p class="text-sm">Weekend Deals</p>
        <h3 class="text-lg font-bold">Headphones & Audio</h3>
      </div>
    </a>
    <a class="relative overflow-hidden rounded-2xl ring-1 ring-slate-200 bg-white group">
      <img src="{{ asset('assets/gaming.jpg') }}" class="h-80 w-full object-cover group-hover:scale-105 transition" alt="">
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
      <div class="absolute bottom-3 left-4 text-white">
        <p class="text-sm">Top Picks</p>
        <h3 class="text-lg font-bold">Gaming Essentials</h3>
      </div>
    </a>
    <a class="relative overflow-hidden rounded-2xl ring-1 ring-slate-200 bg-white group">
      <img src="{{ asset('assets/laptops.jpg') }}" class="h-80 w-full object-cover group-hover:scale-105 transition" alt="">
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
      <div class="absolute bottom-3 left-4 text-white">
        <p class="text-sm">Limitless Work</p>
        <h3 class="text-lg font-bold">Laptops & Monitors</h3>
      </div>
    </a>
  </div>
</section>





<section class="py-12 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Heading -->
    <div class="text-center mb-12">
      <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">🔥 Featured Products</h2>
      <p class="mt-2 text-gray-600">Hand-picked items just for you</p>
    </div>

    <!-- Products grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
      @foreach(range(1, 8) as $i) {{-- Replace this with $products --}}
        <div class="group relative bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition overflow-hidden">
          <!-- Product image -->
          <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100">
            <img src="https://via.placeholder.com/300x200?text=Product+{{ $i }}" 
                 alt="Product {{ $i }}" 
                 class="h-40 sm:h-48 md:h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105">
            <div class="absolute top-3 left-3">
              <span class="inline-flex items-center rounded-md bg-red-500 px-2 py-1 text-xs font-semibold text-white shadow">
                -20%
              </span>
            </div>
          </div>

          <!-- Product info -->
          <div class="p-3 sm:p-4">
            <h3 class="text-xs sm:text-sm font-semibold text-gray-900 group-hover:text-indigo-600 truncate">
              Product {{ $i }}
            </h3>
            <div class="mt-1 flex items-center gap-2">
              <span class="text-sm sm:text-base font-bold text-indigo-600">Rs. {{ number_format(9999, 0) }}</span>
              <span class="text-xs sm:text-sm line-through text-gray-400">Rs. {{ number_format(12499, 0) }}</span>
            </div>
            <p class="mt-1 text-[10px] sm:text-xs text-gray-500">In stock</p>

            <!-- CTA -->
            <a href="#"
               class="mt-3 inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-2 py-1.5 sm:px-3 sm:py-2 text-xs sm:text-sm font-semibold text-white shadow hover:bg-indigo-700">
              Add to Cart
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>




    {{-- Product Section --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold">New Arrival</h2>
                <h5 class="text-green-600">Our Collection</h5>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach ($products->take(16) as $row)
                    <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
                        <a href="{{ url('/products/' . str_replace(' ', '-', $row->name) . '/' . $row->id) }}">
                            <img src="{{ asset('assets/product/' . $row->name . '.png') }}" alt="{{ $row->name }}"
                                class="w-full h-40 object-contain mb-3">
                        </a>

                        <div class="flex justify-between items-center mb-2">



                            <span class="text-green-600 font-bold">Rs.{{ formatNepaliCurrency($row->rate) }}</span>

                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                                {{ number_format(($row->discount / $row->rate) * 100, 0) }}% Off
                            </span>
                        </div>

                        <a href="{{ url('/products/' . str_replace(' ', '-', $row->name) . '/' . $row->id) }}">
                            <h5 class="text-sm font-semibold text-gray-800">
                                {{ Str::limit($row->name, 40, '...') }}
                            </h5>
                        </a>

                        {{-- Rating --}}
                        <div class="flex mt-2 text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star text-gray-300"></i>
                            <i class="fas fa-star text-gray-300"></i>
                            <i class="fas fa-star text-gray-300"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

