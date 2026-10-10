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

          <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
            <h2 class="text-sm font-semibold text-gray-800">Variants (same model, different RAM / storage / colour)</h2>
            <p class="mt-1 text-xs text-gray-500">Each variant is its own product with its own price, stock and SKU. Products sharing a group name are shown as option buttons on each other's page. Leave both fields empty for a product with no variants.</p>
            <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <label class="mb-1 block text-sm font-medium">Variant group name</label>
                <input type="text" name="variant_group" list="variant-groups" value="{{ old('variant_group', $product->variantGroup?->name) }}"
                       placeholder="e.g. Dell Inspiron 14 7440"
                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <datalist id="variant-groups">
                  @foreach(\App\Models\VariantGroup::orderBy('name')->pluck('name') as $groupName)
                  <option value="{{ $groupName }}"></option>
                  @endforeach
                </datalist>
                @error('variant_group') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="mb-1 block text-sm font-medium">This product's options (one per line)</label>
                <textarea name="variant_options" rows="3" placeholder="RAM: 12GB&#10;Storage: 512GB SSD&#10;Colour: Silver"
                          class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('variant_options', collect($product->variant_options ?? [])->map(fn ($v, $k) => "$k: $v")->implode("\n")) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Use the same option names on every variant in the group.</p>
                @error('variant_options') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div>
            <label class="mb-1 block text-sm font-medium">More Photos (gallery)</label>
            <input type="file" name="gallery[]" accept="image/jpeg,image/png,image/webp" multiple class="block w-full text-sm text-gray-900">
            <p class="mt-1 text-xs text-gray-500">Select several at once: other angles, ports, box contents. Up to 10, 5 MB each. They are added to the photos below.</p>
            @error('gallery') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            @error('gallery.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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

        <div class="mt-8 border-t border-gray-200 pt-6">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-sm font-semibold text-gray-800">Variants of this product</h2>
            <form action="{{ route('product.variant.create', $product) }}" method="POST"
                  onsubmit="return confirm('Create a new variant copied from this product? Save any unsaved changes above first.')">
              @csrf
              <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">+ Add a variant (copy this product)</button>
            </form>
          </div>
          @php $siblings = $product->variant_group_id ? \App\Models\Product::where('variant_group_id', $product->variant_group_id)->orderBy('id')->get() : collect(); @endphp
          @if($siblings->count() > 1)
          <div class="mt-3 overflow-x-auto">
            <table class="w-full text-left text-sm">
              <thead class="text-xs uppercase text-gray-500"><tr><th class="py-2 pr-4">Product</th><th class="py-2 pr-4">Options</th><th class="py-2 pr-4">Price</th><th class="py-2 pr-4">Stock</th><th class="py-2">Status</th></tr></thead>
              <tbody class="divide-y divide-gray-100">
                @foreach($siblings as $sibling)
                <tr>
                  <td class="py-2 pr-4">
                    @if($sibling->id === $product->id)
                      <span class="font-semibold text-gray-900">{{ $sibling->name }}</span> <span class="text-xs text-gray-500">(this one)</span>
                    @else
                      <a href="{{ route('product.edit', $sibling) }}" class="font-medium text-indigo-600 hover:underline">{{ $sibling->name }}</a>
                    @endif
                    <div class="text-xs text-gray-500">{{ $sibling->sku }}</div>
                  </td>
                  <td class="py-2 pr-4 text-gray-700">{{ collect($sibling->variant_options ?? [])->map(fn ($v, $k) => "$k: $v")->implode(' · ') ?: '— not set —' }}</td>
                  <td class="py-2 pr-4">Rs. {{ number_format($sibling->final_price, 0) }}</td>
                  <td class="py-2 pr-4">{{ $sibling->quantity }}</td>
                  <td class="py-2">{{ $sibling->is_active ? 'Active' : 'Hidden' }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <p class="mt-2 text-xs text-gray-500">No other variants yet.</p>
          @endif
        </div>

        @if($product->images->count())
        <div class="mt-8 border-t border-gray-200 pt-6">
          <h2 class="mb-3 text-sm font-semibold text-gray-800">Gallery photos</h2>
          <div class="flex flex-wrap gap-4">
            @foreach($product->images as $image)
            <div class="text-center">
              <img src="{{ asset($image->path) }}" alt="" class="h-24 w-24 rounded object-cover ring-1 ring-gray-200">
              <form action="{{ route('product.image.destroy', [$product, $image]) }}" method="POST" onsubmit="return confirm('Remove this photo?')">
                @csrf @method('DELETE')
                <button type="submit" class="mt-1 text-xs font-medium text-red-600 hover:text-red-800">Remove</button>
              </form>
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
