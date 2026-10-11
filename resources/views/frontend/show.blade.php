@extends('frontend.layouts.app')

@section('title', $meta_title)
@section('meta_description', $meta_description)
@section('meta_keywords', $meta_keywords)

@section('content')
    <section class="min-h-screen bg-white py-6 sm:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">

            {{-- Breadcrumbs --}}
            <nav class="mb-4 text-[10px] sm:text-xs text-gray-500">
                <ol class="flex flex-wrap items-center gap-1.5">
                    <li><a href="{{ url('/') }}" class="hover:text-gray-700">Home</a></li>
                    <li>/</li>
                    @if ($product->category)
                        <li><a href="#" class="hover:text-gray-700">{{ $product->category->name }}</a></li>
                        <li>/</li>
                    @endif
                    <li class="text-gray-700 line-clamp-1">{{ $product->titleParts()[0] }}</li>
                </ol>
            </nav>

            @php
                [$title, $features] = $product->titleParts();
                $specs    = $product->specifications;
                $inStock  = $product->stock_status === 'in_stock' && $product->quantity > 0;
                $onSale   = $product->sale_price && $product->sale_price < $product->price;
                $about    = trim((string) $product->description);
                $hasAbout = str_contains($about, ' ');
            @endphp

            {{-- Main --}}
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-12">
                {{-- Left: Gallery (stays in view while the details scroll) --}}
                <div class="lg:col-span-6">
                    <div class="lg:sticky lg:top-24">
                        @include('frontend.partials.gallery', ['photos' => $product->gallery(), 'alt' => $product->name])
                    </div>
                </div>

                {{-- Right: Info --}}
                <div class="lg:col-span-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        @if ($product->category)
                            <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $product->category->name }}</span>
                        @endif
                        @if ($product->brand)
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">{{ $product->brand->name }}</span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold leading-tight text-gray-900">{{ $title }}</h1>

                    @if ($features)
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach ($features as $feature)
                                <li class="rounded-lg bg-gray-100 px-3 py-1 text-xs sm:text-sm font-medium text-gray-700">{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($hasAbout)
                        <p class="mt-4 text-sm leading-relaxed text-gray-600">{{ \Illuminate\Support\Str::limit($about, 220) }}</p>
                    @endif

                    {{-- Price --}}
                    <div class="mt-5 rounded-2xl border border-gray-100 bg-gray-50 p-5">
                        <div class="flex flex-wrap items-end gap-x-3 gap-y-1">
                            <span class="text-3xl sm:text-4xl font-bold text-gray-900">Rs. {{ number_format($product->final_price, 0) }}</span>
                            @if ($onSale)
                                <span class="mb-1 text-lg text-gray-400 line-through">Rs. {{ number_format($product->price, 0) }}</span>
                                <span class="mb-1.5 rounded-full bg-rose-500 px-2 py-0.5 text-xs font-bold text-white">-{{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) }}%</span>
                                <span class="mb-1.5 text-sm font-semibold text-rose-600">You save Rs. {{ number_format($product->price - $product->sale_price, 0) }}</span>
                            @endif
                        </div>
                        <p class="mt-2 flex items-center gap-2 text-sm font-semibold {{ $inStock ? 'text-emerald-600' : 'text-rose-600' }}">
                            <span class="h-2 w-2 rounded-full {{ $inStock ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            @if (! $inStock) Out of stock
                            @elseif ($product->quantity <= 5) Only {{ $product->quantity }} left in stock
                            @else In stock
                            @endif
                        </p>
                    </div>

                    <div class="mt-5 space-y-5">
                    {{-- Options: each choice is its own product with its own price and stock --}}
                    @foreach ($variantChoices as $optionName => $options)
                        <div>
                            <p class="mb-2 text-xs sm:text-sm text-gray-600">
                                {{ $optionName }}:
                                <span class="font-semibold text-gray-900">{{ $product->variant_options[$optionName] ?? '' }}</span>
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($options as $option)
                                    @php
                                        $isColour = in_array(strtolower($optionName), ['color', 'colour']);
                                        $state = $option['current']
                                            ? 'border-blue-600 bg-blue-50 text-blue-700 ring-1 ring-blue-600'
                                            : ($option['exact']
                                                ? 'border-gray-300 bg-white text-gray-800 hover:border-gray-500'
                                                : 'border-dashed border-gray-300 bg-white text-gray-500 hover:border-gray-500');
                                    @endphp
                                    <a href="{{ url('/products/'.$option['product']->slug.'/'.$option['product']->id) }}"
                                        @if ($option['current']) aria-current="true" @endif
                                        title="{{ $option['product']->name }} — Rs. {{ number_format($option['product']->final_price, 0) }}{{ $option['in_stock'] ? '' : ' (out of stock)' }}"
                                        class="inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-xs sm:text-sm font-medium transition {{ $state }} {{ $option['in_stock'] ? '' : 'opacity-50 line-through' }}">
                                        @if ($isColour && $option['product']->image)
                                            <img src="{{ asset($option['product']->image) }}" alt="" class="h-7 w-7 rounded object-contain" loading="lazy">
                                        @endif
                                        {{ $option['value'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    </div>

                    {{-- Success flash --}}
                    @if (session('success'))
                        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-center">
                            <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Quantity + Add to cart --}}
                    @if ($product->quantity > 0)
                        <div class="mt-5 flex flex-wrap items-stretch gap-3">
                            <div class="inline-flex items-center overflow-hidden rounded-xl border border-gray-200 bg-white">
                                <button type="button" id="decrease-btn" aria-label="Decrease quantity"
                                    class="px-4 py-3 text-lg text-gray-600 hover:bg-gray-50 disabled:opacity-40"
                                    onclick="updateQuantity(-1)">–</button>
                                <input id="quantity" aria-label="Quantity"
                                    class="w-12 border-0 py-3 text-center text-sm font-semibold focus:ring-0"
                                    value="1" readonly>
                                <button type="button" id="increase-btn" aria-label="Increase quantity"
                                    class="px-4 py-3 text-lg text-gray-600 hover:bg-gray-50 disabled:opacity-40"
                                    onclick="updateQuantity(1)">+</button>
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST" class="flex-1 min-w-[12rem]">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" id="form-quantity" name="quantity" value="1">
                                <button type="submit"
                                    class="inline-flex h-full w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3.5 text-base font-semibold text-white shadow-sm transition hover:bg-blue-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Max per order: <b>{{ $maxPerOrder }}</b></p>
                    @else
                        <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-center">
                            <p class="text-sm font-semibold text-rose-700">Out of stock</p>
                            <p class="mt-0.5 text-xs text-rose-600">This item is currently unavailable</p>
                        </div>
                    @endif

                    {{-- Buying info --}}
                    <ul class="mt-5 grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
                        @foreach ([
                            ['M5 13l4 4L19 7', 'Cash on delivery'],
                            ['M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0', 'Delivery across Nepal'],
                            ['M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'Order confirmation by email'],
                        ] as [$icon, $label])
                            <li class="flex items-center gap-2.5 rounded-xl border border-gray-100 px-3 py-3 font-medium text-gray-700">
                                <svg class="h-5 w-5 flex-shrink-0 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                                {{ $label }}
                            </li>
                        @endforeach
                    </ul>

                    {{-- Key facts --}}
                    <dl class="mt-5 divide-y divide-gray-100 border-t border-gray-100 text-sm">
                        <div class="flex justify-between gap-6 py-2.5"><dt class="text-gray-500">SKU</dt><dd class="font-medium text-gray-900">{{ $product->sku }}</dd></div>
                        @foreach ($specs->take(4) as $spec)
                            <div class="flex justify-between gap-6 py-2.5"><dt class="flex-shrink-0 text-gray-500">{{ $spec->name }}</dt><dd class="text-right font-medium text-gray-900">{{ $spec->value }}</dd></div>
                        @endforeach
                    </dl>
                    @if ($specs->count() > 4)
                        <a href="#specifications" class="mt-3 inline-block text-sm font-semibold text-blue-700 hover:text-blue-800">See all {{ $specs->count() }} specifications ↓</a>
                    @endif

                    <div class="mt-5">
                    {{-- Share (compact) --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-3">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] sm:text-xs text-gray-600">Share</p>
                            <div class="flex items-center gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank" rel="noopener"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M24 12.073C24 5.446 18.627 0 12 0S0 5.446 0 12.073c0 5.99 4.388 10.953 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                                <a href="whatsapp://send?text={{ urlencode(url()->current()) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}"
                                    target="_blank" rel="noopener"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if ($hasAbout && \Illuminate\Support\Str::length($about) > 220)
                <div class="mt-10 rounded-2xl border border-gray-200 bg-white p-5 sm:p-8">
                    <h2 class="mb-4 text-lg sm:text-xl font-bold text-gray-900">Product Description</h2>
                    <div class="text-sm leading-relaxed text-gray-700">{!! nl2br(e($about)) !!}</div>
                </div>
            @endif

            {{-- Specs: two columns on wide screens --}}
            @if ($specs->count())
                <div id="specifications" class="mt-8 scroll-mt-24 rounded-2xl border border-gray-200 bg-white p-5 sm:p-8">
                    <h2 class="mb-4 text-lg sm:text-xl font-bold text-gray-900">Specifications</h2>
                    <dl class="grid text-sm md:grid-cols-2 md:gap-x-12">
                        @foreach ($specs as $spec)
                            <div class="flex gap-4 border-b border-gray-100 py-3">
                                <dt class="w-2/5 flex-shrink-0 font-medium text-gray-500">{{ $spec->name }}</dt>
                                <dd class="text-gray-900">{{ $spec->value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif

            {{-- You may also like --}}
            <section class="mt-10">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-sm sm:text-base font-semibold text-gray-900">You may also like</h2>
                    {{-- (Optional) View all link --}}
                    {{-- <a href="#" class="text-xs text-gray-500 hover:text-gray-700">View all</a> --}}
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4">
                    @forelse($related as $rp)
                        <a href="{{ url('/products/' . $rp->slug . '/' . $rp->id) }}"
                            class="group block overflow-hidden rounded-xl border border-gray-200 bg-white hover:shadow-sm">
                            <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-50">
                                <img src="{{ asset( $rp->image) }}" alt="{{ $rp->image }}"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @if ($rp->sale_price && $rp->sale_price < $rp->price)
                                    <span
                                        class="absolute left-2 top-2 rounded-md bg-rose-600 px-1.5 py-0.5 text-[10px] font-semibold text-white">
                                        -{{ number_format((($rp->price - $rp->sale_price) / $rp->price) * 100, 0) }}%
                                    </span>
                                @endif
                            </div>
                            <div class="p-2.5 sm:p-3">
                                <h3
                                    class="truncate text-[12px] sm:text-sm font-medium text-gray-900 group-hover:text-gray-700">
                                    {{ $rp->name }}
                                </h3>
                                <div class="mt-1 flex items-center gap-1.5">
                                    <span class="text-[12px] sm:text-sm font-semibold text-gray-900">
                                        Rs. {{ number_format($rp->final_price, 0) }}
                                    </span>
                                    @if ($rp->sale_price && $rp->sale_price < $rp->price)
                                        <span class="text-[10px] sm:text-xs text-gray-400 line-through">
                                            Rs. {{ number_format($rp->price, 0) }}
                                        </span>
                                    @endif
                                </div>
                                @if ($rp->brand)
                                    <p class="mt-0.5 text-[10px] text-gray-500">{{ $rp->brand->name }}</p>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p class="col-span-4 text-center text-xs text-gray-500">No related products found.</p>
                    @endforelse
                </div>
            </section>

        </div>
    </section>

    {{-- Quantity JS (respect stock cap) --}}
    <script>
        const MAX_QTY = {{ $maxPerOrder }};

        function updateQuantity(change) {
            const qInput = document.getElementById('quantity');
            const formQ = document.getElementById('form-quantity');
            const dec = document.getElementById('decrease-btn');
            const inc = document.getElementById('increase-btn');

            let q = parseInt(qInput.value || '1', 10);
            q = Math.min(MAX_QTY, Math.max(1, q + change));

            qInput.value = q;
            if (formQ) formQ.value = q;

            dec.disabled = q <= 1;
            inc.disabled = q >= MAX_QTY;
        }
        document.addEventListener('DOMContentLoaded', () => updateQuantity(0));
    </script>
@endsection
