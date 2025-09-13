@extends('frontend.layouts.app')

@section('content')
<section class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">

    {{-- ====== Global Toasts (Success & Error) ====== --}}
    <div class="relative" aria-live="polite" aria-atomic="true">
      @if (session('success'))
        <div id="toast-success" class="fixed top-4 right-4 z-50 flex w-full max-w-md items-start gap-3 rounded-2xl bg-white/90 p-4 shadow-2xl ring-1 ring-emerald-200 backdrop-blur">
          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div class="flex-1">
            <p class="font-semibold text-emerald-800">Success</p>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
          </div>
          <button type="button" class="rounded-lg p-2 text-emerald-700/70 hover:bg-emerald-50" onclick="this.parentElement.remove()">✕</button>
        </div>
      @endif

      @if ($errors->any())
        <div id="toast-error" class="fixed top-4 right-4 z-50 flex w-full max-w-md items-start gap-3 rounded-2xl bg-white/90 p-4 shadow-2xl ring-1 ring-red-200 backdrop-blur">
          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 text-red-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div class="flex-1">
            <p class="font-semibold text-red-800">Something went wrong</p>
            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
          </div>
          <button type="button" class="rounded-lg p-2 text-red-700/70 hover:bg-red-50" onclick="this.parentElement.remove()">✕</button>
        </div>
      @endif
    </div>

    {{-- ====== Empty Cart ====== --}}
    @if ($isEmptyCart)
      <div class="text-center space-y-4">
        <h2 class="text-3xl font-bold text-gray-900">Your cart is empty</h2>
        <p class="text-gray-600">It looks like you have no items in your cart. Start shopping now!</p>
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-700">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Continue Shopping
        </a>
      </div>

      {{-- Past Orders --}}
      <div class="mt-10 overflow-x-auto">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
          <div class="border-b bg-gray-50 p-4">
            <h3 class="text-lg font-semibold text-gray-800">Your past orders</h3>
          </div>
          <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
              <tr>
                <th class="px-6 py-3 text-left font-medium">Order ID</th>
                <th class="px-6 py-3 text-left font-medium">Date</th>
                <th class="px-6 py-3 text-left font-medium">Name</th>
                <th class="px-6 py-3 text-left font-medium">Qty</th>
                <th class="px-6 py-3 text-left font-medium">Rate</th>
                <th class="px-6 py-3 text-left font-medium">Total</th>
                <th class="px-6 py-3 text-left font-medium">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @forelse ($orders as $order)
                @foreach ($order->items as $item)
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">{{ $order->id }}</td>
                    <td class="px-6 py-3">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-3">{{ $item->product_name }}</td>
                    <td class="px-6 py-3">{{ $item->quantity }}</td>
                    <td class="px-6 py-3">Rs.{{ number_format($item->price,2) }}</td>
                    <td class="px-6 py-3">Rs.{{ number_format($item->total,2) }}</td>
                    <td class="px-6 py-3">
                      <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-700">
                        {{ $item->status ?? '—' }}
                      </span>
                    </td>
                  </tr>
                @endforeach
              @empty
                <tr>
                  <td colspan="7" class="px-6 py-6 text-center text-gray-500">No past orders found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    {{-- ====== Cart With Products ====== --}}
    @else
      <div class="overflow-x-auto">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
          <div class="border-b bg-gray-50 p-4">
            <h3 class="text-lg font-semibold text-gray-800">Your cart</h3>
          </div>

          <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
              <tr>
                <th class="px-6 py-3 text-left font-medium">Image</th>
                <th class="px-6 py-3 text-left font-medium">Product</th>
                <th class="px-6 py-3 text-left font-medium">Price</th>
                <th class="px-6 py-3 text-left font-medium">Quantity</th>
                <th class="px-6 py-3 text-left font-medium">Total</th>
                <th class="px-6 py-3 text-left font-medium">Action</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
              @foreach ($products as $product)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4">
                    <a href="#">
                      <img src="{{ asset('storage/product/'. $product['image_url']) }}"
                           class="h-16 w-16 rounded object-cover ring-1 ring-gray-200"
                           alt="{{ $product['image_url'] }}">
                    </a>
                  </td>

                  <td class="px-6 py-4 align-top">
                    <a href="#" class="font-semibold text-gray-900 hover:text-blue-600">{{ $product['name'] }}</a>
                    @isset($product['available'])
                      <p class="mt-1 text-xs text-gray-500">In stock: {{ $product['available'] }}</p>
                    @endisset
                  </td>

                  <td class="px-6 py-4 font-semibold text-gray-800">
                    Rs.{{ number_format($product['rate'], 2) }}
                  </td>

                  {{-- Quantity controls + inline error --}}
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <form action="{{ route('cart.updateQuantity', $product['id']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity_action" value="decrease">
                        <button type="submit"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-200 text-gray-800 transition hover:bg-gray-300"
                                title="Decrease">
                          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </button>
                      </form>

                      <span class="inline-flex min-w-[3rem] items-center justify-center rounded-lg border border-gray-200 bg-white px-2 py-1 font-semibold">
                        {{ $product['quantity'] }}
                      </span>

                      <form action="{{ route('cart.updateQuantity', $product['id']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity_action" value="increase">
                        <button type="submit"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-200 text-gray-800 transition hover:bg-gray-300"
                                title="Increase">
                          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                          </svg>
                        </button>
                      </form>
                    </div>

                    @if ($errors->has('quantity'))
                      <p class="mt-2 inline-flex items-center gap-2 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 ring-1 ring-red-200">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M12 9v4m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ $errors->first('quantity') }}
                      </p>
                    @endif
                  </td>

                  <td class="px-6 py-4 font-semibold text-gray-800">
                    Rs.{{ number_format($product['total'], 2) }}
                  </td>

                  <td class="px-6 py-4">
                    <form action="{{ route('cart.remove') }}" method="POST">
                      @csrf
                      <input type="hidden" name="id" value="{{ $product['id'] }}">
                      <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700 ring-1 ring-red-200 transition hover:bg-red-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8l-1-2.5A2 2 0 0013.118 3H10.88a2 2 0 00-1.882 1.5L8 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
      <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Clear all items from cart?')">
          @csrf
          <input type="hidden" name="user_id" value="{{ Auth::id() }}">
          <button class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2 font-semibold text-gray-700 ring-1 ring-gray-200 transition hover:bg-gray-200">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M8 6v12m8-12v12M5 6l.867 12.142A2 2 0 007.862 20h8.276a2 2 0 001.995-1.858L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Clear All Items
          </button>
        </form>

        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-700">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Continue Shopping
        </a>
      </div>

      {{-- Totals / Checkout --}}
      <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
        <div>
          <form class="flex gap-2">
            <input type="text" class="flex-1 rounded-xl border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Coupon Code">
            <button class="rounded-xl bg-blue-600 px-4 py-2 font-semibold text-white transition hover:bg-blue-700">Apply</button>
          </form>
        </div>

        <div class="md:col-span-2">
          <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-3 text-lg font-semibold text-gray-900">Cart Totals</h3>
            <div class="space-y-2 text-sm">
              <div class="flex items-center justify-between">
                <span class="text-gray-600">Sub Total</span>
                <span class="font-semibold">Rs.{{ $subtotal }}</span>
              </div>
              <div class="flex items-center justify-between text-base font-bold">
                <span>Total</span>
                <span>Rs.{{ $total }}</span>
              </div>
            </div>

            {{-- Go to Checkout (CartController@index) --}}
            <a href="{{ route('order.create', ['name' => $product['name'], 'id' => $product['id']]) }}"
               class="mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-emerald-700">
              Checkout
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
          </div>
        </div>
      </div>
    @endif
  </div>
</section>

<script>
  setTimeout(() => document.getElementById('toast-success')?.remove(), 4500);
  setTimeout(() => document.getElementById('toast-error')?.remove(), 5000);
</script>
@endsection
