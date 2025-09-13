@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-7xl">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h3 class="text-2xl font-bold tracking-tight text-gray-900">Products</h3>
      <a href="{{ route('product.create') }}"
         class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
        + Add New Product
      </a>
    </div>

    {{-- Search --}}
    <form action="{{ route('product.index') }}" method="GET" class="mb-6">
      <div class="grid grid-cols-1 gap-3 sm:grid-cols-12">
        <div class="sm:col-span-9">
          <input type="search" name="search" value="{{ $search }}"
                 placeholder="Search by name"
                 class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="sm:col-span-3">
          <button class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            Search
          </button>
        </div>
      </div>
    </form>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      {{-- Table wrapper for horizontal scroll on small screens --}}
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-left text-gray-600">
            <tr>
              <th class="px-4 py-3 font-medium">SN</th>
              <th class="px-4 py-3 font-medium">Name</th>
              <th class="px-4 py-3 font-medium">Brand</th>
              <th class="px-4 py-3 font-medium">Rate</th>
              <th class="px-4 py-3 font-medium">Discount</th>
              <th class="px-4 py-3 font-medium">Quantity</th>
              <th class="px-4 py-3 font-medium">Description</th>
              <th class="px-4 py-3 font-medium">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            @forelse ($products as $row)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-700">{{ $row->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $row->name }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $row->brand }}</td>
                <td class="px-4 py-3 text-gray-800">Rs. {{ number_format($row->rate) }}</td>
                <td class="px-4 py-3 text-gray-800">Rs. {{ number_format($row->discount) }}</td>
                <td class="px-4 py-3">
                  @php $q = (int) $row->quantity; @endphp
                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                    {{ $q <= 0 ? 'bg-red-100 text-red-700' : ($q < 10 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                    {{ $q <= 0 ? 'Out of stock' : $row->quantity }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-700 max-w-[18rem] truncate" title="{{ $row->description }}">
                  {{ $row->description }}
                </td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('product.edit', $row->id) }}" method="GET">
                      @csrf
                      <button type="submit"
                              class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Update
                      </button>
                    </form>

                    <form action="{{ route('product.destroy', $row->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-700">
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                  No products found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
        <div class="flex justify-center">
          {{ $products->onEachSide(1)->links('pagination::tailwind') }}
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
