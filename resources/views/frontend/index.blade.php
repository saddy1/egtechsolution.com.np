@extends('frontend.layouts.app')
@push('head')
    <title>EG Tech Solution</title>
@endpush
@section('title', $meta_title)
@section('meta_description', $meta_description)


@section('content')




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





{{-- 🔹 Featured Products --}}
<section class="py-12 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Heading -->
    <div class="text-center mb-12">
      <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">🔥 Featured Products</h2>
      <p class="mt-2 text-gray-600">Hand-picked items just for you</p>
    </div>

    <!-- Products grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
      @forelse($featuredProducts as $product)
        <div class="group relative bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition overflow-hidden">
          <!-- Product image -->
          <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100 relative">
            <img src="{{ asset('storage/product/'.$product->image) }}" alt="{{ $product->image }}"
                 class="h-40 sm:h-48 md:h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105">
            
            @if($product->sale_price && $product->sale_price < $product->price)
              <div class="absolute top-3 left-3">
                <span class="inline-flex items-center rounded-md bg-red-500 px-2 py-1 text-xs font-semibold text-white shadow">
                  -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100) }}%
                </span>
              </div>
            @endif
          </div>

          <!-- Product info -->
          <div class="p-3 sm:p-4">
            <h3 class="text-xs sm:text-sm font-semibold text-gray-900 group-hover:text-indigo-600 truncate">
              
              <a href="{{ url('/products/' . $product->slug . '/' . $product->id) }}">{{ $product->name }}</a>
            </h3>
            <div class="mt-1 flex items-center gap-2">
              <span class="text-sm sm:text-base font-bold text-indigo-600">
                Rs. {{ number_format($product->final_price, 0) }}
              </span>
              @if($product->sale_price && $product->sale_price < $product->price)
                <span class="text-xs sm:text-sm line-through text-gray-400">
                  Rs. {{ number_format($product->price, 0) }}
                </span>
              @endif
            </div>
            <p class="mt-1 text-[10px] sm:text-xs text-gray-500">
              {{ $product->stock_status === 'in_stock' ? 'In stock' : 'Out of stock' }}
            </p>

            <!-- CTA -->
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





 
@endsection

