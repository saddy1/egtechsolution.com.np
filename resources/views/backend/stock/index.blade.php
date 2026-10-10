@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
      <h2 class="text-2xl font-bold text-gray-900">📦 Stock — Products</h2>

      <div class="flex items-center gap-2">
        {{-- Search --}}
        <form action="{{ route('stock.stock.index') }}" method="GET" class="flex items-center gap-2">
          <input
            type="text"
            name="q"
            value="{{ $q ?? request('q') }}"
            placeholder="Search name / SKU / barcode / supplier"
            class="w-72 rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
          >
          <button
            class="rounded-xl bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 hover:bg-gray-200"
            type="submit">
            Search
          </button>
          @if(!empty($q))
            <a href="{{ route('stock.stock.index') }}"
               class="rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50">
              Clear
            </a>
          @endif
        </form>

        {{-- Add Product --}}
        <a href="{{ route('product.create') }}"
           class="rounded-xl bg-indigo-600 px-4 py-2 text-white text-sm font-semibold hover:bg-indigo-700 transition">
          + Add Product
        </a>
      </div>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-xl bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      @if(!empty($q))
        <div class="px-4 py-2 text-sm text-gray-600 bg-gray-50 border-b">
          Showing results for <span class="font-semibold">“{{ $q }}”</span>
        </div>
      @endif

      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left w-64">Product</th>
            <th class="px-4 py-3 text-left">Supplier</th>
            <th class="px-4 py-3 text-right">Qty</th>
            <th class="px-4 py-3 text-right">Cost</th>
            <th class="px-4 py-3 text-right">Regular</th>
            <th class="px-4 py-3 text-right">Selling</th>
            <th class="px-4 py-3 text-right">View</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100 text-sm">
          @forelse ($products as $p)
            @php
              $low = (int)$p->quantity <= 5;
              $critical = (int)$p->quantity <= 2;
            @endphp
            <tr class="{{ $low ? 'bg-rose-50/60' : 'bg-white' }} hover:bg-gray-50 transition">
              <td class="px-4 py-3">
                <div class="font-semibold text-gray-900">{{ $p->name }}</div>
                <div class="text-xs text-gray-500">SKU: {{ $p->sku }}</div>
              </td>
              <td class="px-4 py-3 text-gray-700">{{ optional($p->supplier)->name ?? '-' }}</td>
              <td class="px-4 py-3 text-right">
                <span class="{{ $critical ? 'text-red-700 font-semibold heartbeat' : ($low ? 'text-amber-600 font-semibold' : 'text-gray-800') }}">
                  {{ $p->quantity }}
                </span>
              </td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($p->cost_price, 2) }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($p->price, 2) }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($p->sale_price, 2) }}</td>
              <td class="px-4 py-3 text-right">
                <a href="{{ route('stock.show', $p) }}"
                   class="rounded-lg bg-indigo-600 text-white px-3 py-1.5 text-xs font-semibold hover:bg-indigo-700">
                  View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                No products found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
  </div>
</section>

<style>
@keyframes heartbeat { 0%{transform:scale(1)} 25%{transform:scale(1.2)} 40%{transform:scale(1)} 60%{transform:scale(1.1)} 100%{transform:scale(1)} }
.heartbeat { animation: heartbeat 1.5s infinite; }
</style>
@endsection
