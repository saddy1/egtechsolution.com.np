@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-7xl">

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-2xl font-bold text-gray-900">Products</h2>
      <a href="{{ route('product.create') }}"
         class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700">
        + Add Product
      </a>
    </div>

    <form method="GET" class="mb-4">
      <div class="flex gap-2">
        <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by name or SKU"
               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
        <button class="rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 hover:bg-gray-200">
          Search
        </button>
      </div>
    </form>

    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
        {{ session('success') }}
      </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-left text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-4 py-3">#</th>
              <th class="px-4 py-3">Image</th>
              <th class="px-4 py-3">Name</th>
              <th class="px-4 py-3">Brand</th>
              <th class="px-4 py-3">Category</th>
              <th class="px-4 py-3">Price</th>
              <th class="px-4 py-3">Qty</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($products as $i => $p)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-700">{{ $products->firstItem() + $i }}</td>
                <td class="px-4 py-3">
                  @if($p->image)
                    <img src="{{ asset('storage/product/'.$p->image) }}" alt="{{ $p->image }}" class="h-12 w-12 rounded object-cover ring-1 ring-gray-200" alt="">
                  @else
                    <div class="h-12 w-12 rounded bg-gray-100 ring-1 ring-gray-200"></div>
                  @endif
                </td>
                <td class="px-4 py-3">
                  <div class="font-semibold text-gray-900">{{ $p->name }}</div>
                  <div class="text-xs text-gray-500">SKU: {{ $p->sku }}</div>
                </td>
                <td class="px-4 py-3">{{ $p->brand?->name ?? '—' }}</td>
                <td class="px-4 py-3">{{ $p->category?->name ?? '—' }}</td>
                <td class="px-4 py-3">
                  <span class="font-semibold text-gray-900">Rs. {{ number_format($p->final_price, 2) }}</span>
                  @if($p->sale_price && $p->sale_price > 0)
                    <span class="ml-1 text-xs text-gray-400 line-through">Rs. {{ number_format($p->price, 2) }}</span>
                  @endif
                </td>
                <td class="px-4 py-3">{{ $p->quantity }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                    {{ $p->stock_status === 'in_stock' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                    {{ $p->stock_status === 'in_stock' ? 'In Stock' : 'Out' }}
                  </span>
                </td>
                <td class="px-4 py-3 space-x-2">
                  <a href="{{ route('product.edit', $p) }}" class="text-indigo-600 hover:underline">Edit</a>
                  <form action="{{ route('product.destroy', $p) }}" method="POST" class="inline"
                        onsubmit="return confirm('Delete this product? This will remove its image file too.')">
                    @csrf @method('DELETE')
                    <button class="text-rose-600 hover:underline">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="9" class="px-4 py-6 text-center text-gray-500">No products found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
        <div class="flex justify-center">
          {{ $products->onEachSide(1)->links('pagination::tailwind') }}
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
