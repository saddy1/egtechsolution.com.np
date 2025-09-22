@extends('frontend.layouts.app')

@section('title', $meta_title)
@section('meta_description', $meta_description)
@section('meta_keywords', $meta_keywords)

@section('content')
    <section class="min-h-screen bg-white py-6 sm:py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-5">

            {{-- Breadcrumbs --}}
            <nav class="mb-4 text-[10px] sm:text-xs text-gray-500">
                <ol class="flex flex-wrap items-center gap-1.5">
                    <li><a href="{{ url('/') }}" class="hover:text-gray-700">Home</a></li>
                    <li>/</li>
                    @if ($product->category)
                        <li><a href="#" class="hover:text-gray-700">{{ $product->category->name }}</a></li>
                        <li>/</li>
                    @endif
                    <li class="text-gray-700">{{ $product->name }}</li>
                </ol>
            </nav>

            {{-- Main --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Left: Gallery --}}
                <div class="order-2 lg:order-1">
                    <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-4">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->image }}"
                            class="h-auto w-full rounded-lg object-contain" style="max-height: 520px" loading="eager">
                    </div>
                    {{-- (Optional) thumbnails row could go here --}}
                </div>

                {{-- Right: Info --}}
                <div class="order-1 space-y-4 lg:order-2">
                    {{-- Title + brand --}}
                    <div>
                        <h1 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 leading-snug">
                            {{ $product->name }}
                        </h1>
                        @if ($product->brand)
                            <p class="mt-1 text-[11px] sm:text-xs text-gray-500">by
                                <span class="font-medium text-gray-700">{{ $product->brand->name }}</span>
                            </p>
                        @endif
                    </div>

                    {{-- Price block --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="text-xl sm:text-2xl font-bold text-gray-900">
                                Rs. {{ number_format($product->final_price, 0) }}
                            </div>
                            @if ($product->sale_price && $product->sale_price < $product->price)
                                <div class="flex items-center gap-2">
                                    <span class="text-xs sm:text-sm text-gray-400 line-through">
                                        Rs. {{ number_format($product->price, 0) }}
                                    </span>
                                    <span
                                        class="inline-flex items-center rounded-full bg-rose-500 px-2 py-0.5 text-[10px] font-semibold text-white">
                                        -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                        <p class="mt-1 text-[11px] sm:text-xs text-gray-500">
                            {{ $product->stock_status === 'in_stock' ? 'In stock' : 'Out of stock' }}
                            @if ($product->quantity > 0)
                                • {{ $product->quantity }} available
                            @endif
                        </p>
                    </div>

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

                    {{-- Quantity + Add to cart --}}
                    @if ($product->quantity > 0)
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="inline-flex items-center overflow-hidden rounded-lg border border-gray-200 bg-white">
                                    <button type="button" id="decrease-btn"
                                        class="px-3 py-2 text-gray-600 hover:bg-gray-50 disabled:opacity-40"
                                        onclick="updateQuantity(-1)">–</button>
                                    <input id="quantity"
                                        class="w-12 border-0 py-2 text-center text-sm font-medium focus:ring-0"
                                        value="1" readonly>
                                    <button type="button" id="increase-btn"
                                        class="px-3 py-2 text-gray-600 hover:bg-gray-50 disabled:opacity-40"
                                        onclick="updateQuantity(1)">+</button>
                                </div>
                                <span class="text-[11px] sm:text-xs text-gray-500">Max per order:
                                    <b>{{ $maxPerOrder }}</b></span>
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST" class="pt-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" id="form-quantity" name="quantity" value="1">
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-black">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-center">
                            <p class="text-sm font-semibold text-rose-700">Out of stock</p>
                            <p class="mt-0.5 text-[11px] text-rose-600">This item is currently unavailable</p>
                        </div>
                    @endif

                    {{-- Success flash --}}
                    @if (session('success'))
                        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-center">
                            <p class="text-[12px] sm:text-sm font-medium text-emerald-700">{{ session('success') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Specs --}}
            <div class="mt-8 rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-200 px-4 py-3 sm:px-5">
                    <h3 class="text-sm font-semibold text-gray-900">Specifications</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-xs sm:text-sm">
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($product->specifications as $spec)
                                <tr>
                                    <td class="w-40 px-4 py-3 font-medium text-gray-700 sm:w-64">{{ $spec->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $spec->value }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">No specifications
                                        available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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
