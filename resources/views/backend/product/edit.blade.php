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

          {{-- Variants: the same model in other RAM / storage sizes, each with its own price and stock --}}
          @php
            $siblings = $product->variant_group_id
                ? \App\Models\Product::where('variant_group_id', $product->variant_group_id)->where('id', '!=', $product->id)->orderBy('id')->get()
                : collect();
          @endphp
          <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
            <h2 class="text-sm font-semibold text-gray-800">Variants (RAM / Storage)</h2>
            <p class="mt-1 text-xs text-gray-500">Customers see one product and switch RAM / storage on its page; each choice has its own price and stock. Leave everything here empty for a product without variants.</p>
            @if($errors->has('variants.*') || $errors->has('new_variants.*') || $errors->has('variant_link_sku'))
              <p class="mt-2 text-sm text-red-600">{{ collect($errors->get('variants.*'))->flatten()->merge(collect($errors->get('new_variants.*'))->flatten())->merge($errors->get('variant_link_sku'))->first() }}</p>
            @endif

            <div class="mt-3 overflow-x-auto">
              <table class="w-full min-w-[860px] text-left text-sm">
                <thead class="text-xs uppercase text-gray-500">
                  <tr>
                    <th class="py-2 pr-2 w-[26%]">Name</th><th class="py-2 pr-2">RAM</th><th class="py-2 pr-2">Storage</th>
                    <th class="py-2 pr-2">Price</th><th class="py-2 pr-2">Sale price</th><th class="py-2 pr-2">Cost price</th>
                    <th class="py-2 pr-2 w-20">Stock</th><th class="py-2 pr-2">Show</th><th class="py-2">Unlink</th>
                  </tr>
                </thead>
                <tbody class="align-top">
                  {{-- This product --}}
                  <tr>
                    <td class="py-1.5 pr-2 text-gray-700"><span class="font-semibold">This product</span><div class="text-xs text-gray-500">{{ $product->sku }}</div></td>
                    <td class="py-1.5 pr-2"><input type="text" name="variant_self[ram]" value="{{ old('variant_self.ram', $product->variant_options['RAM'] ?? '') }}" placeholder="16GB" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="text" name="variant_self[storage]" value="{{ old('variant_self.storage', $product->variant_options['Storage'] ?? '') }}" placeholder="512GB SSD" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2 text-xs text-gray-500" colspan="6">Price and stock of this one are the fields above.</td>
                  </tr>
                  {{-- Its other variants --}}
                  @foreach($siblings as $v)
                  <tr>
                    <td class="py-1.5 pr-2"><input type="text" name="variants[{{ $v->id }}][name]" value="{{ old("variants.$v->id.name", $v->name) }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"><a href="{{ route('product.edit', $v) }}" class="text-xs text-indigo-600 hover:underline">{{ $v->sku }} · photos &amp; details →</a></td>
                    <td class="py-1.5 pr-2"><input type="text" name="variants[{{ $v->id }}][ram]" value="{{ old("variants.$v->id.ram", $v->variant_options['RAM'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="text" name="variants[{{ $v->id }}][storage]" value="{{ old("variants.$v->id.storage", $v->variant_options['Storage'] ?? '') }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="number" step="0.01" min="0" name="variants[{{ $v->id }}][price]" value="{{ old("variants.$v->id.price", $v->price) }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="number" step="0.01" min="0" name="variants[{{ $v->id }}][sale_price]" value="{{ old("variants.$v->id.sale_price", $v->sale_price) }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="number" step="0.01" min="0" name="variants[{{ $v->id }}][cost_price]" value="{{ old("variants.$v->id.cost_price", $v->cost_price) }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="number" min="0" name="variants[{{ $v->id }}][quantity]" value="{{ old("variants.$v->id.quantity", $v->quantity) }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2 pt-3"><input type="checkbox" name="variants[{{ $v->id }}][is_active]" value="1" @checked(old("variants.$v->id.is_active", $v->is_active))></td>
                    <td class="py-1.5 pt-3"><input type="checkbox" name="variants[{{ $v->id }}][unlink]" value="1" title="Make this a separate product again"></td>
                  </tr>
                  @endforeach
                  {{-- New variants --}}
                  @foreach([0, 1] as $n)
                  <tr>
                    <td class="py-1.5 pr-2"><input type="text" name="new_variants[{{ $n }}][name]" value="{{ old("new_variants.$n.name") }}" placeholder="+ New variant (name optional)" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="text" name="new_variants[{{ $n }}][ram]" value="{{ old("new_variants.$n.ram") }}" placeholder="16GB" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="text" name="new_variants[{{ $n }}][storage]" value="{{ old("new_variants.$n.storage") }}" placeholder="1TB SSD" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="number" step="0.01" min="0" name="new_variants[{{ $n }}][price]" value="{{ old("new_variants.$n.price") }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="number" step="0.01" min="0" name="new_variants[{{ $n }}][sale_price]" value="{{ old("new_variants.$n.sale_price") }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="number" step="0.01" min="0" name="new_variants[{{ $n }}][cost_price]" value="{{ old("new_variants.$n.cost_price") }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td class="py-1.5 pr-2"><input type="number" min="0" name="new_variants[{{ $n }}][quantity]" value="{{ old("new_variants.$n.quantity") }}" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></td>
                    <td colspan="2"></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <p class="mt-2 text-xs text-gray-500">To add a variant, fill a "New variant" row (RAM or storage, and price) and save; it copies this product's description, specifications and main photo. Need more rows? Save and two new empty rows appear.</p>

            <div class="mt-4 max-w-md">
              <label class="mb-1 block text-xs font-medium text-gray-700">Already added it as a separate product? Enter its SKU to join it to this one</label>
              <input type="text" name="variant_link_sku" value="{{ old('variant_link_sku') }}" placeholder="SKU of the other product" class="w-full rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
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
