{{-- One product tile. Expects $product. --}}
@php
    $url = url('/products/' . $product->slug . '/' . $product->id);
    $onSale = $product->sale_price && $product->sale_price < $product->price;
    $inStock = $product->stock_status === 'in_stock';
@endphp
<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-1 hover:border-slate-300 hover:shadow-lg">
  <a href="{{ $url }}" class="relative block aspect-square overflow-hidden bg-slate-100">
    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy"
         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
    @if ($onSale)
      <span class="absolute left-3 top-3 rounded-md bg-brand-orange px-2 py-1 text-xs font-semibold text-white">
        -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100) }}%
      </span>
    @endif
  </a>

  <div class="flex flex-1 flex-col p-3 sm:p-4">
    <h3 class="line-clamp-2 min-h-[2.5rem] text-sm font-semibold leading-5 text-slate-900">
      <a href="{{ $url }}" class="hover:text-brand-navy">{{ $product->name }}</a>
    </h3>

    <div class="mt-2 flex flex-wrap items-baseline gap-x-2">
      <span class="text-base font-bold text-brand-navy sm:text-lg">Rs. {{ number_format($product->final_price, 0) }}</span>
      @if ($onSale)
        <span class="text-xs text-slate-400 line-through sm:text-sm">Rs. {{ number_format($product->price, 0) }}</span>
      @endif
    </div>

    <p class="mt-1 flex items-center gap-1.5 text-xs {{ $inStock ? 'text-emerald-700' : 'text-slate-500' }}">
      <span class="h-1.5 w-1.5 rounded-full {{ $inStock ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
      {{ $inStock ? 'In stock' : 'Out of stock' }}
    </p>

    <div class="mt-auto pt-3">
      @if ($inStock)
        <form action="{{ route('cart.add') }}" method="POST">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <input type="hidden" name="quantity" value="1">
          <button type="submit"
                  class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-orange px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-orange-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-orange">
            <i class="fa-solid fa-cart-plus" aria-hidden="true"></i> Add to cart
          </button>
        </form>
      @else
        <a href="{{ $url }}"
           class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
          View details
        </a>
      @endif
    </div>
  </div>
</article>
