@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-6xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h5 class="text-2xl font-bold tracking-tight text-gray-900">Edit Product</h5>
      <a href="{{ route('product.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 transition hover:bg-gray-200">
        ← Back to Products
      </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="border-b bg-gray-50 px-5 py-4">
        <p class="text-sm text-gray-600">Update details below.</p>
      </div>

      <div class="px-5 py-6">
        <form action="{{ route('product.update', $product) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-6">
          @csrf
          @method('PUT')

          {{-- Basic Info --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-6">
              <label class="mb-1 block text-sm font-medium">Product Name *</label>
              <input type="text" name="name" value="{{ old('name', $product->name) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
              @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label class="mb-1 block text-sm font-medium">SKU *</label>
              <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
              @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label class="mb-1 block text-sm font-medium">Barcode</label>
              <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('barcode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Pricing --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">

            <div class="md:col-span-2">
              <label class="mb-1 block text-sm font-medium">Cost Price (Rs.) *</label>
              <input type="number" name="cost_price" step="0.01" min="0" value="{{ old('cost_price', $product->cost_price) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
              @error('cost_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            
            <div class="md:col-span-2">
              <label class="mb-1 block text-sm font-medium">Regular Price (Rs.) *</label>
              <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
              @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
              <label class="mb-1 block text-sm font-medium">Sale Price (Rs.)</label>
              <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('sale_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label class="mb-1 block text-sm font-medium">Quantity *</label>
              <input type="number" name="quantity" step="1" min="0" value="{{ old('quantity', $product->quantity) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
              @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label class="mb-1 block text-sm font-medium">Stock Status *</label>
              <select name="stock_status"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <option value="in_stock" @selected(old('stock_status', $product->stock_status)==='in_stock')>In Stock</option>
                <option value="out_of_stock" @selected(old('stock_status', $product->stock_status)==='out_of_stock')>Out of Stock</option>
              </select>
              @error('stock_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Category & Brand --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-4">
              <label class="mb-1 block text-sm font-medium">Category *</label>
              <select name="category_id"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id)==$category->id)>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-4">
              <label class="mb-1 block text-sm font-medium">Brand *</label>
              <select name="brand_id"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                @foreach($brands as $brand)
                  <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id)==$brand->id)>
                    {{ $brand->name }}
                  </option>
                @endforeach
              </select>
              @error('brand_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-4">
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                  <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm">Active</span>
                  </label>
                </div>
                <div>
                  <label class="mb-2 block text-sm font-medium text-gray-700">Featured</label>
                  <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm">Featured</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          {{-- Description --}}
          <div>
            <label class="mb-1 block text-sm font-medium">Description *</label>
            <textarea name="description" rows="4" required
                      class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          {{-- SEO --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-6">
              <label class="mb-1 block text-sm font-medium">Meta Title</label>
              <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="md:col-span-6">
              <label class="mb-1 block text-sm font-medium">Meta Description</label>
              <textarea name="meta_description" rows="2"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('meta_description', $product->meta_description) }}</textarea>
            </div>
          </div>

          {{-- Image (replace) --}}
          <div>
            <label class="mb-1 block text-sm font-medium">Product Image</label>
            <div class="flex items-start gap-4">
              @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->image }}" class="h-20 w-20 rounded object-cover ring-1 ring-gray-200" alt="">
              @endif

              <div class="flex-1">
                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-900">
                <p class="mt-1 text-xs text-gray-500">Upload to replace the current image (optional).</p>
                @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
            <a href="{{ route('product.index') }}" class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 hover:bg-gray-200">
              Cancel
            </a>
            <button class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700">
              Update Product
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
