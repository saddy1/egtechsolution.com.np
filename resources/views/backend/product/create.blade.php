{{-- resources/views/backend/product/create.blade.php --}}
@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-6xl">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h5 class="text-2xl font-bold tracking-tight text-gray-900">Create Product</h5>
      <a href="{{ route('product.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 transition hover:bg-gray-200">
        ← Back to Products
      </a>
    </div>

    {{-- Card --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="border-b bg-gray-50 px-5 py-4">
        <p class="text-sm text-gray-600">Fill out details below. Fields marked * are required.</p>
      </div>

      <div class="px-5 py-6">
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-6">
          @csrf

          {{-- Global form errors --}}
          @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
              <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Basic Info --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-6">
              <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Product Name *</label>
              <input type="text" name="name" id="name" value="{{ old('name') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                     required>
              @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-3">
              <label for="sku" class="mb-1 block text-sm font-medium text-gray-700">SKU *</label>
              <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                     required>
              @error('sku')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-3">
              <label for="barcode" class="mb-1 block text-sm font-medium text-gray-700">Barcode</label>
              <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('barcode')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Pricing --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-3">
              <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Regular Price (Rs.) *</label>
              <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                     required>
              @error('price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-3">
              <label for="sale_price" class="mb-1 block text-sm font-medium text-gray-700">Sale Price (Rs.)</label>
              <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('sale_price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-3">
              <label for="quantity" class="mb-1 block text-sm font-medium text-gray-700">Quantity *</label>
              <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" step="1" min="0"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                     required>
              @error('quantity')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-3">
              <label for="stock_status" class="mb-1 block text-sm font-medium text-gray-700">Stock Status *</label>
              <select name="stock_status" id="stock_status"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <option value="in_stock" @selected(old('stock_status')==='in_stock')>In Stock</option>
                <option value="out_of_stock" @selected(old('stock_status')==='out_of_stock')>Out of Stock</option>
              </select>
              @error('stock_status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Categories & Brand --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-4">
              <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700">Category *</label>
              <select name="category_id" id="category_id"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-4">
              <label for="brand_id" class="mb-1 block text-sm font-medium text-gray-700">Brand *</label>
              <select name="brand_id" id="brand_id"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                <option value="">Select Brand</option>
                @foreach ($brands as $brand)
                  <option value="{{ $brand->id }}" @selected(old('brand_id')==$brand->id)>{{ $brand->name }}</option>
                @endforeach
              </select>
              @error('brand_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-4">
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                  <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Active</span>
                  </label>
                </div>
                <div>
                  <label class="mb-2 block text-sm font-medium text-gray-700">Featured</label>
                  <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured'))
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Featured</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          {{-- Slug (optional, auto from name) --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-6">
              <label for="slug" class="mb-1 block text-sm font-medium text-gray-700">Slug (URL)</label>
              <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              <p class="mt-1 text-xs text-gray-500">Auto-generated from product name. You can edit if needed.</p>
              @error('slug')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Description --}}
          <div class="grid grid-cols-1 gap-6">
            <div>
              <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Description *</label>
              <textarea name="description" id="description" rows="4" required
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
              @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Specifications --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-12">
              <label for="specifications" class="mb-1 block text-sm font-medium text-gray-700">Product Specifications</label>
              <textarea name="specifications" id="specifications" rows="6"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        placeholder="Enter specifications, one per line. Example:&#10;RAM: 8GB&#10;Storage: 256GB SSD&#10;Display: 15.6 inch">{{ old('specifications') }}</textarea>
              @error('specifications')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
              <p class="mt-1 text-xs text-gray-500">Tip: Enter one specification per line, like "RAM: 8GB"</p>
            </div>
          </div>

          {{-- SEO Fields --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-6">
              <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700">Meta Title (SEO)</label>
              <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('meta_title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-6">
              <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700">Meta Description (SEO)</label>
              <textarea name="meta_description" id="meta_description" rows="2"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('meta_description') }}</textarea>
              @error('meta_description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-8">
              <label for="meta_keywords" class="mb-1 block text-sm font-medium text-gray-700">Meta Keywords (comma-separated)</label>
              <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('meta_keywords')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            <div class="md:col-span-4 flex items-end">
              <button type="button" id="btn-generate-seo"
                class="w-full inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                ⚡ Auto-Generate SEO
              </button>
            </div>
          </div>

          {{-- Image Upload --}}
          <div class="grid grid-cols-1 gap-6">
            <div>
              <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Product Image *</label>

              <input type="file" name="image" id="image" accept="image/*" class="sr-only" required>

              <div id="drag-drop-area"
                   class="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center transition hover:border-indigo-400 hover:bg-indigo-50">
                <div class="rounded-xl bg-white p-3 shadow-sm ring-1 ring-gray-200">
                  <svg class="h-6 w-6 text-gray-600" viewBox="0 0 24 24" fill="none">
                    <path d="M12 16v-8m0 0l-3 3m3-3l3 3M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">Drag & drop an image here, or <span class="text-indigo-600 underline">browse</span></p>
                <p class="text-xs text-gray-500">PNG, JPG up to ~5MB</p>
                <p id="file-name" class="mt-1 text-xs font-medium text-gray-600"></p>

                <img id="preview"
                     class="mt-4 hidden max-h-48 rounded-xl object-contain ring-1 ring-gray-200"
                     alt="Preview">
              </div>

              @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Actions --}}
          <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
            <a href="{{ route('product.index') }}"
               class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 transition hover:bg-gray-200">
              Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
              Create Product
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</section>

{{-- Page Scripts --}}
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // CSRF (ensure your layout has meta tag)
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Elements
    const area = document.getElementById("drag-drop-area");
    const input = document.getElementById("image");
    const fileName = document.getElementById("file-name");
    const preview = document.getElementById("preview");

    const nameInput = document.getElementById("name");
    const skuInput = document.getElementById("sku");
    const slugInput = document.getElementById("slug");
    const btnSeo = document.getElementById("btn-generate-seo");

    const metaTitle = document.getElementById('meta_title');
    const metaDesc  = document.getElementById('meta_description');
    const metaKeys  = document.getElementById('meta_keywords');

    // Helpers
    const slugify = (s) =>
      s.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');

    function showFile(f) {
      fileName.textContent = f ? f.name : "";
      if (f && f.type && f.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = (e) => {
          preview.src = e.target.result;
          preview.classList.remove("hidden");
        };
        reader.readAsDataURL(f);
      } else {
        preview.classList.add("hidden");
        preview.src = "";
      }
    }

    // Drag & Drop
    area.addEventListener("click", () => input.click());
    area.addEventListener("dragover", (e) => {
      e.preventDefault();
      area.classList.add("border-indigo-500", "bg-indigo-50");
    });
    area.addEventListener("dragleave", () => {
      area.classList.remove("border-indigo-500", "bg-indigo-50");
    });
    area.addEventListener("drop", (e) => {
      e.preventDefault();
      area.classList.remove("border-indigo-500", "bg-indigo-50");
      const files = e.dataTransfer.files;
      if (files.length) {
        input.files = files;
        showFile(files[0]);
      }
    });
    input.addEventListener("change", () => {
      const f = input.files?.[0];
      showFile(f);
    });

    // Auto-generate SKU and Slug from Name
    nameInput.addEventListener("input", () => {
      const val = nameInput.value || '';
      if (slugInput && !slugInput.dataset.touched) {
        slugInput.value = slugify(val);
      }
      if (skuInput && !skuInput.value) {
        const base = val.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 8);
        const rand = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        skuInput.value = base ? (base + rand) : '';
      }
    });

    // If user edits slug manually, stop auto updates
    if (slugInput) {
      slugInput.addEventListener('input', () => {
        slugInput.dataset.touched = '1';
      });
    }

    // One-click SEO using your backend endpoint
    if (btnSeo) {
      btnSeo.addEventListener('click', async () => {
        const name = nameInput?.value?.trim();
        if (!name) {
          alert('Please enter the Product Name first.');
          return;
        }
        const catSel = document.getElementById('category_id');
        const category = catSel && catSel.options[catSel.selectedIndex]?.text?.trim() || 'General';
        const description = document.getElementById('description')?.value?.trim() || '';
        const specifications = document.getElementById('specifications')?.value?.trim() || '';
        const features = [description, specifications].filter(Boolean).join("\n");

        btnSeo.disabled = true;
        btnSeo.textContent = 'Generating…';

        try {
          const res = await fetch(`{{ route('product.generateSeo') }}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json',
            },
            body: JSON.stringify({ name, category, features })
          });

          const data = await res.json();
          if (!res.ok) throw new Error(data?.message || 'Failed to generate');

          if (metaTitle && data.meta_title) metaTitle.value = data.meta_title;
          if (metaDesc  && data.meta_description) metaDesc.value = data.meta_description;
          if (metaKeys  && data.meta_keywords) metaKeys.value = data.meta_keywords;
          if (slugInput && data.slug && !slugInput.dataset.touched) slugInput.value = data.slug;

        } catch (e) {
          console.error(e);
          alert('Could not generate SEO. Please try again.');
        } finally {
          btnSeo.disabled = false;
          btnSeo.textContent = '⚡ Auto-Generate SEO';
        }
      });
    }
  });
</script>
@endsection
