@extends('frontend.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-b from-white to-slate-50 py-6 sm:py-8 px-3 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">

            {{-- ====== Toasts ====== --}}
            <div class="relative" aria-live="polite" aria-atomic="true">
                @if (session('success'))
                    <div id="toast-success"
                        class="fixed top-3 right-3 z-50 flex w-full max-w-sm items-start gap-2 rounded-xl bg-white/95 p-3 shadow-2xl ring-1 ring-emerald-200 backdrop-blur">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-emerald-800">Success</p>
                            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="rounded-md p-1.5 text-emerald-700/70 hover:bg-emerald-50"
                            onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div id="toast-error"
                        class="fixed top-3 right-3 z-50 flex w-full max-w-sm items-start gap-2 rounded-xl bg-white/95 p-3 shadow-2xl ring-1 ring-red-200 backdrop-blur">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 text-red-700">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <path d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-red-800">Something went wrong</p>
                            <p class="text-xs text-red-700">{{ $errors->first() }}</p>
                        </div>
                        <button type="button" class="rounded-md p-1.5 text-red-700/70 hover:bg-red-50"
                            onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif
            </div>

            {{-- ====== Empty Cart ====== --}}
            @if ($isEmptyCart)
                <div class="text-center space-y-3 sm:space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Your cart is empty</h2>
                    <p class="text-sm sm:text-base text-slate-600">Looks like you haven’t added anything yet.</p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Continue Shopping
                    </a>
                </div>

           {{-- Past Orders --}}
<div class="mt-6 overflow-x-auto">
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b bg-slate-50 px-4 py-2">
            <h3 class="text-sm sm:text-base font-semibold text-slate-800">Your Past Orders</h3>
        </div>

        <table class="min-w-full text-xs sm:text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="px-3 py-2 text-left font-medium">Order ID</th>
                    <th class="px-3 py-2 text-left font-medium">Date</th>
                    <th class="px-3 py-2 text-left font-medium">Name</th>
                    <th class="px-3 py-2 text-left font-medium">Qty</th>
                    <th class="px-3 py-2 text-left font-medium">Rate</th>
                    <th class="px-3 py-2 text-left font-medium">Total</th>
                    <th class="px-3 py-2 text-left font-medium">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse ($orders as $order)
                    @foreach ($order->items as $item)
                        @php
                            $status = strtolower($item->status ?? '—');
                            $statusColor = match ($status) {
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-blue-100 text-blue-700',
                                'shipped' => 'bg-indigo-100 text-indigo-700',
                                'delivered' => 'bg-green-100 text-green-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-3 py-2 font-medium text-slate-700">{{ $order->id }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $order->created_at->format('d M Y') }}</td>

                            {{-- Scrollable product name --}}
                            <td class="px-3 py-2">
                                <div class="max-w-[100px] sm:max-w-[160px] overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-transparent">
                                    {{ $item->product_name }}
                                </div>
                            </td>

                            <td class="px-3 py-2 text-slate-600">{{ $item->quantity }}</td>
                            <td class="px-3 py-2 text-slate-600">Rs.{{ number_format($item->price, 2) }}</td>
                            <td class="px-3 py-2 font-semibold text-slate-700">
                                Rs.{{ number_format($item->total, 2) }}
                            </td>

                            <td class="px-3 py-2">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] sm:text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst($item->status ?? '—') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-4 text-center text-slate-500">
                            No past orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Optional scrollbar styling (only works if Tailwind Scrollbar plugin is enabled) --}}
<style>
    /* Hide scrollbar for WebKit browsers if plugin not available */
    ::-webkit-scrollbar {
        height: 4px;
    }
    ::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }
</style>


                {{-- ====== Cart With Products ====== --}}
            @else
                {{-- Mobile list (cards) --}}
                <div class="space-y-3 sm:hidden">
                    @foreach ($products as $product)
                        <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                            <div class="flex gap-3">
                                <img src="{{ $product['image_url'] }}"
                                    class="h-16 w-16 rounded-lg object-cover ring-1 ring-slate-200"
                                    alt="{{ $product['name'] }}">
                                <div class="flex-1 min-w-0">
                                    <h4 class="truncate text-sm font-semibold text-slate-900">{{ $product['name'] }}</h4>
                                    @isset($product['available'])
                                        <p class="mt-0.5 text-[11px] text-slate-500">In stock: {{ $product['available'] }}</p>
                                    @endisset
                                    <div class="mt-1.5 flex items-center justify-between">
                                        <p class="text-sm font-bold text-indigo-600">
                                            Rs.{{ number_format($product['rate'], 2) }}</p>
                                        <p class="text-xs font-semibold text-slate-800">Total:
                                            Rs.{{ number_format($product['total'], 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <form action="{{ route('cart.updateQuantity', $product['id']) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity_action" value="decrease">
                                        <button type="submit"
                                            class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-100 text-slate-800 hover:bg-slate-200">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 12h14" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </button>
                                    </form>
                                    <span
                                        class="inline-flex min-w-[2.25rem] items-center justify-center rounded-md border border-slate-200 bg-white px-2 py-1 text-xs font-semibold">
                                        {{ $product['quantity'] }}
                                    </span>
                                    <form action="{{ route('cart.updateQuantity', $product['id']) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity_action" value="increase">
                                        <button type="submit"
                                            class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-100 text-slate-800 hover:bg-slate-200">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">
                                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product['id'] }}">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8l-1-2.5A2 2 0 0013.118 3H10.88a2 2 0 00-1.882 1.5L8 7"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        Remove
                                    </button>
                                </form>
                            </div>

                            @if ($errors->has('quantity'))
                                <p
                                    class="mt-2 inline-flex items-center gap-1.5 rounded-md bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 ring-1 ring-red-200">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 9v4m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ $errors->first('quantity') }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Desktop table --}}
                <div class="overflow-x-auto hidden sm:block mt-4">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b bg-slate-50 p-4">
                            <h3 class="text-base font-semibold text-slate-800">Your cart</h3>
                        </div>

                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-100 text-slate-600">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium">Image</th>
                                    <th class="px-6 py-3 text-left font-medium">Product</th>
                                    <th class="px-6 py-3 text-left font-medium">Price</th>
                                    <th class="px-6 py-3 text-left font-medium">Quantity</th>
                                    <th class="px-6 py-3 text-left font-medium">Total</th>
                                    <th class="px-6 py-3 text-left font-medium">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @foreach ($products as $product)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <img src="{{ asset($product['image_url']) }}"
                                                class="h-16 w-16 rounded object-cover ring-1 ring-slate-200"
                                                alt="{{ $product['image_url'] }}">
                                        </td>

                                        <td class="px-6 py-4 align-top">
                                            <a href="#"
                                                class="font-semibold text-slate-900 hover:text-indigo-600">{{ $product['name'] }}</a>
                                            @isset($product['available'])
                                                <p class="mt-1 text-xs text-slate-500">In stock: {{ $product['available'] }}
                                                </p>
                                            @endisset
                                        </td>

                                        <td class="px-6 py-4 font-semibold text-slate-800">
                                            Rs.{{ number_format($product['rate'], 2) }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('cart.updateQuantity', $product['id']) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="quantity_action" value="decrease">
                                                    <button type="submit"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200"
                                                        title="Decrease">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                            <path d="M5 12h14" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <span
                                                    class="inline-flex min-w-[3rem] items-center justify-center rounded-lg border border-slate-200 bg-white px-2 py-1 font-semibold">
                                                    {{ $product['quantity'] }}
                                                </span>

                                                <form action="{{ route('cart.updateQuantity', $product['id']) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="quantity_action" value="increase">
                                                    <button type="submit"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200"
                                                        title="Increase">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                            <path d="M12 5v14M5 12h14" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>

                                            @php $rowErrorKey = 'qty_'.$product['id']; @endphp
                                            @if ($errors->has($rowErrorKey))
                                                <p
                                                    class="mt-2 inline-flex items-center gap-2 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 ring-1 ring-red-200">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                        <path d="M12 9v4m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    {{ $errors->first($rowErrorKey) }}
                                                </p>
                                            @endif

                                        </td>

                                        <td class="px-6 py-4 font-semibold text-slate-800">
                                            Rs.{{ number_format($product['total'], 2) }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $product['id'] }}">
                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-xl bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8l-1-2.5A2 2 0 0013.118 3H10.88a2 2 0 00-1.882 1.5L8 7"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer actions --}}
                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                    <form action="{{ route('cart.clear') }}" method="POST"
                        onsubmit="return confirm('Clear all items from cart?')">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        <button
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-200">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M3 6h18M8 6v12m8-12v12M5 6l.867 12.142A2 2 0 007.862 20h8.276a2 2 0 001.995-1.858L19 6"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Clear All Items
                        </button>
                    </form>

                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Continue Shopping
                    </a>
                </div>

                {{-- Totals / Checkout --}}
                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <form class="flex gap-2">
                            <input type="text"
                                class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                placeholder="Coupon Code">
                            <button
                                class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Apply</button>
                        </form>
                    </div>

                    <div class="md:col-span-2">
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h3 class="mb-3 text-base font-semibold text-slate-900">Cart Totals</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600">Sub Total</span>
                                    <span class="font-semibold">Rs.{{ $subtotal }}</span>
                                </div>
                                <div class="flex items-center justify-between text-base font-bold">
                                    <span>Total</span>
                                    <span>Rs.{{ $total }}</span>
                                </div>
                            </div>

                            {{-- Go to checkout page (adjust route if yours is different) --}}
                            <a href="{{ route('order.create', ['name' => $product['name'], 'id' => $product['id']]) }}"
                                class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-emerald-700">
                                Checkout
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        setTimeout(() => document.getElementById('toast-success')?.remove(), 4200);
        setTimeout(() => document.getElementById('toast-error')?.remove(), 4800);
    </script>
@endsection
