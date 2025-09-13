@extends('frontend.layouts.app')

@section('content')
<section class="w-full px-4 sm:px-6 lg:px-8 py-8">

  {{-- Header / summary --}}
  <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
    <h1 class="text-xl font-bold text-slate-900">Search & Filters</h1>
    @if(request()->hasAny(['search','category','max_price']))
      <a href="{{ route('home') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
        Clear all & Return Home
      </a>
    @endif
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-10 gap-6">

    {{-- ===== Sidebar (20%) ===== --}}
    <aside class="lg:col-span-2">
  <form id="filterForm" action="{{ url()->current() }}" method="GET"
        class="space-y-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

    {{-- Keep search term in form --}}
    <input type="hidden" name="search" value="{{ $search }}"/>

    {{-- Price Filter --}}
    <div>
      <h4 class="mb-2 text-sm font-semibold text-slate-900">Price</h4>
      <div class="flex items-center justify-between text-xs text-slate-600">
        <span>0</span>
        <span>Max: <b id="priceValue">{{ number_format($maxPrice) }}</b></span>
      </div>
      <input
        type="range"
        name="max_price"
        id="max_price"
        min="0"
        max="{{ $maxFromDb }}"
        value="{{ $maxPrice }}"
        class="mt-2 w-full accent-red-600"
        oninput="document.getElementById('priceValue').textContent = Number(this.value).toLocaleString()"
        onchange="document.getElementById('filterForm').submit()"
      />
    </div>

    {{-- Category Filter --}}
    <div>
      <h4 class="mb-2 text-sm font-semibold text-slate-900">Category</h4>
      <ul class="max-h-60 space-y-2 overflow-y-auto pr-1">
        @foreach($categories as $cat)
          <li>
            <label class="flex cursor-pointer items-center gap-2 text-sm">
              <input
                type="checkbox"
                name="category[]"
                value="{{ $cat->id }}"
                class="h-4 w-4 rounded border-slate-300 text-red-600 focus:ring-red-500"
                {{ in_array($cat->id, $selectedCategoryIds ?? []) ? 'checked' : '' }}
                onchange="document.getElementById('filterForm').submit()"
              >
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
        @foreach($brands as $brand)
          <li>
            <label class="flex cursor-pointer items-center gap-2 text-sm">
              <input
                type="checkbox"
                name="brand[]"
                value="{{ $brand->id }}"
                class="h-4 w-4 rounded border-slate-300 text-red-600 focus:ring-red-500"
                {{ in_array($brand->id, $selectedBrandIds ?? []) ? 'checked' : '' }}
                onchange="document.getElementById('filterForm').submit()"
              >
              <span class="flex-1 text-slate-700">
                {{ $brand->name }}
                <span class="ml-1 text-xs text-slate-500">({{ $brand->products_count }})</span>
              </span>
            </label>
          </li>
        @endforeach
      </ul>
    </div>

    {{-- Clear filters (stay on search page) + Home --}}
    @if(request()->has('category') || request()->has('brand') || request()->has('max_price') || $search !== '')
      <div class="flex gap-2">
        <a href="{{ url()->current() }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200 w-full text-center">
          Clear filters
        </a>
        <a href="{{ route('home') }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200 w-full text-center">
          Home
        </a>
      </div>
    @endif
  </form>
</aside>


    {{-- ===== Results (80%) — NO BANNERS HERE ===== --}}
    <div class="lg:col-span-8">
      @if($products->count() === 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-800">
          No products found for your filters.
        </div>
      @else
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
          @foreach($products as $p)
            <a href="{{ url('/products/' . $p->slug . '/' . $p->id) }}" class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
              <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-slate-100">
                <img
                  src="{{ $p->image ? asset('storage/product/'.$p->image) : 'https://via.placeholder.com/400x300?text=Product' }}"
                  alt="{{ $p->name }}"
                  class="h-40 sm:h-48 md:h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                   @if ($p->sale_price && $p->sale_price < $p->price)
                                <div class="absolute top-3 left-3">
                                    <span
                                        class="inline-flex items-center rounded-md bg-red-500 px-2 py-1 text-xs font-semibold text-white shadow">
                                        -{{ number_format((($p->price - $p->sale_price) / $p->price) * 100) }}%
                                    </span>
                                </div>
                            @endif
              </div>
              <div class="p-3 sm:p-4">
                <h3 class="truncate text-sm font-semibold text-slate-900 group-hover:text-indigo-600">{{ $p->name }}</h3>
                <div class="mt-1 flex items-center gap-2">
                  <span class="text-sm font-bold text-indigo-600">
                    Rs. {{ number_format($p->sale_price ?? $p->price) }}
                  </span>
                  @if($p->sale_price)
                    <span class="text-xs line-through text-slate-400">
                      Rs. {{ number_format($p->price) }}
                    </span>
                  @endif
                </div>
              </div>
            </a>
          @endforeach
        </div>

        <div class="mt-6">
          {{ $products->appends(request()->query())->links() }}
        </div>
      @endif
    </div>

  </div>
</section>
@endsection
