{{-- resources/views/backend/product/create.blade.php --}}
@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-6xl">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Create Product</h1>
      <a href="{{ route('product.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 transition hover:bg-gray-200">
        ← Back to Products
      </a>
    </div>

    {{-- Card --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="border-b bg-gray-50 px-5 py-4">
        <p class="text-sm text-gray-600">Fill out the details below. Fields marked <span class="font-semibold">*</span> are required.</p>
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
              @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label for="sku" class="mb-1 block text-sm font-medium text-gray-700">SKU *</label>
              <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                     required>
              @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label for="barcode" class="mb-1 block text-sm font-medium text-gray-700">Barcode</label>
              <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('barcode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Pricing / Stock --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-2">
              <label for="cost_price" class="mb-1 block text-sm font-medium text-gray-700">Cost Price (Rs.) *</label>
              <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price') }}" step="0.01" min="0"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                     required>
              @error('cost_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
              <label for="sale_price" class="mb-1 block text-sm font-medium text-gray-700">Sale Price (Rs.) *</label>
              <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('sale_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Regular Price (Rs.) *</label>
              <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                     required>
              @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
              <label for="quantity" class="mb-1 block text-sm font-medium text-gray-700">Quantity *</label>
              <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" step="1" min="0"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                     required>
              @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label for="stock_status" class="mb-1 block text-sm font-medium text-gray-700">Stock Status *</label>
              <select name="stock_status" id="stock_status"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <option value="in_stock" @selected(old('stock_status')==='in_stock')>In Stock</option>
                <option value="out_of_stock" @selected(old('stock_status')==='out_of_stock')>Out of Stock</option>
              </select>
              @error('stock_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Categories & Brand --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-4">
              <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700">Category *</label>
              <select name="category_id" id="category_id"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-4">
              <label for="brand_id" class="mb-1 block text-sm font-medium text-gray-700">Brand *</label>
              <select name="brand_id" id="brand_id"
                      class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" required>
                <option value="">Select Brand</option>
                @foreach ($brands as $brand)
                  <option value="{{ $brand->id }}" @selected(old('brand_id')==$brand->id)>{{ $brand->name }}</option>
                @endforeach
              </select>
              @error('brand_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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

          {{-- Slug --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-6">
              <label for="slug" class="mb-1 block text-sm font-medium text-gray-700">Slug (URL)</label>
              <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              <p class="mt-1 text-xs text-gray-500">Auto-generated from product name. You can edit if needed.</p>
              @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Description --}}
          <div class="grid grid-cols-1 gap-6">
            <div>
              <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Description *</label>
              <textarea name="description" id="description" rows="4" required
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
              @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Specifications --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-12">
              <label for="specifications" class="mb-1 block text-sm font-medium text-gray-700">Product Specifications</label>
              <textarea name="specifications" id="specifications" rows="6"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        placeholder="Enter specifications, one per line. Example:&#10;RAM: 8GB&#10;Storage: 256GB SSD&#10;Display: 15.6 inch">{{ old('specifications') }}</textarea>
              @error('specifications') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
              <p class="mt-1 text-xs text-gray-500">Tip: Enter one specification per line, like "RAM: 8GB"</p>
            </div>
          </div>

          {{-- SEO Fields --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-6">
              <label for="meta_title" class="mb-1 block text-sm font-medium text-gray-700">Meta Title (SEO)</label>
              <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('meta_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-6">
              <label for="meta_description" class="mb-1 block text-sm font-medium text-gray-700">Meta Description (SEO)</label>
              <textarea name="meta_description" id="meta_description" rows="2"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">{{ old('meta_description') }}</textarea>
              @error('meta_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-8">
              <label for="meta_keywords" class="mb-1 block text-sm font-medium text-gray-700">Meta Keywords (comma-separated)</label>
              <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('meta_keywords') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-4 flex items-end">
              <button type="button" id="btn-generate-seo"
                class="w-full inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                ⚡ Auto-Generate SEO
              </button>
            </div>
          </div>

          {{-- Image Upload (Drag & Drop / Click / Paste / URL drops) --}}
          <div class="grid grid-cols-1 gap-6">
            <div>
              <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Product Image *</label>
              <input type="file" name="image" id="image" accept="image/*" class="sr-only" required>

              <div id="drag-drop-area"
                   tabindex="0"
                   role="button"
                   aria-label="Upload product image by drag and drop or click to browse"
                   class="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center transition hover:border-indigo-400 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="rounded-xl bg-white p-3 shadow-sm ring-1 ring-gray-200">
                  <svg class="h-6 w-6 text-gray-600" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 16V8m0 0l-3 3m3-3l3 3M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">
                  Drag & drop an image file or image link here, or <span class="text-indigo-600 underline">browse</span>
                </p>
                <p class="text-xs text-gray-500">PNG, JPG, WEBP, GIF up to 5MB</p>

                <p id="file-name" class="mt-1 text-xs font-medium text-gray-600"></p>

                <img id="preview"
                     class="mt-4 hidden max-h-60 w-auto rounded-xl object-contain ring-1 ring-gray-200"
                     alt="Preview">
                <div id="loader" class="mt-3 hidden text-xs text-gray-600">Fetching image…</div>
              </div>

              <p id="image-error" class="mt-2 hidden text-sm text-red-600"></p>
              @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Actions --}}
          <div class="sticky bottom-0 z-10 -mx-5 border-t bg-white/80 px-5 pt-4 backdrop-blur sm:static sm:border-none sm:bg-transparent sm:px-0 sm:pt-2">
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
              <a href="{{ route('product.index') }}"
                 class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 transition hover:bg-gray-200">
                Cancel
              </a>
              <button type="submit"
                      class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                Create Product
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</section>

{{-- Page Scripts --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
  const area      = document.getElementById("drag-drop-area");
  const input     = document.getElementById("image");
  const fileName  = document.getElementById("file-name");
  const preview   = document.getElementById("preview");
  const imgErr    = document.getElementById("image-error");
  const loader    = document.getElementById("loader");

  // SEO field refs (needed for generator)
  const metaTitle = document.getElementById('meta_title');
  const metaDesc  = document.getElementById('meta_description');
  const metaKeys  = document.getElementById('meta_keywords');

  const MAX_SIZE = 5 * 1024 * 1024; // 5MB

  // ---------- helpers ----------
  function setError(msg) {
    if (!msg) { imgErr.classList.add('hidden'); imgErr.textContent=''; }
    else { imgErr.textContent = msg; imgErr.classList.remove('hidden'); }
  }
  function toggleLoader(show){ loader?.classList.toggle('hidden', !show); }
  function validateFile(f) {
    if (!f) return false;
    if (!/^image\/(png|jpe?g|webp|gif)$/i.test(f.type)) { setError('Use PNG, JPG, WEBP, or GIF.'); return false; }
    if (f.size > MAX_SIZE) { setError('Image is too large. Max 5MB.'); return false; }
    setError(''); return true;
  }
  function showPreviewFromFile(file) {
    fileName.textContent = file ? file.name : '';
    if (file && validateFile(file)) {
      const reader = new FileReader();
      reader.onload = (e) => { preview.src = e.target.result; preview.classList.remove('hidden'); };
      reader.readAsDataURL(file);
    } else {
      preview.classList.add('hidden'); preview.src = '';
    }
  }
  function assignToInput(file) {
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    showPreviewFromFile(file);
  }
  function preventDefaults(e){ e.preventDefault(); e.stopPropagation(); }
  function highlight(){ area.classList.add('border-indigo-500','bg-indigo-50'); }
  function unhighlight(){ area.classList.remove('border-indigo-500','bg-indigo-50'); }

  // Extract a dropped URL (from other tab/page)
  function getDroppedUrl(e){
    const dt = e.dataTransfer;
    const uriList = dt?.getData('text/uri-list'); if (uriList) return uriList.split('\n')[0].trim();
    const plain = dt?.getData('text/plain'); if (plain && /^https?:\/\//i.test(plain.trim())) return plain.trim();
    const html = dt?.getData('text/html');
    if (html) {
      const m = html.match(/src\s*=\s*["']([^"']+)["']/i) || html.match(/href\s*=\s*["']([^"']+)["']/i);
      if (m && m[1]) return m[1];
    }
    return null;
  }

  // Try to fetch a remote URL client-side (works only if CORS allows)
  async function fetchAsFile(url){
    toggleLoader(true);
    try {
      const res = await fetch(url, { mode: 'cors', credentials: 'omit' });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const ct = (res.headers.get('content-type') || '').toLowerCase();
      if (!/^image\//.test(ct)) throw new Error('Not an image');
      const blob = await res.blob();
      // filename from URL
      const pathname = new URL(url).pathname;
      let base = pathname ? pathname.split('/').pop() : '';
      if (!/\.(png|jpe?g|webp|gif)$/i.test(base)) {
        const ext = (ct.match(/^image\/(\w+)/)?.[1] || 'png').replace('jpeg','jpg');
        base = `image-${Math.random().toString(36).slice(2,8)}.${ext}`;
      }
      return new File([blob], base, { type: blob.type });
    } finally {
      toggleLoader(false);
    }
  }

  // ---------- global safety to stop browser from opening files ----------
  ['dragover','drop'].forEach(evt => {
    document.addEventListener(evt, (e) => {
      if (!(e.target instanceof HTMLInputElement) && !(e.target instanceof HTMLTextAreaElement)) {
        preventDefaults(e);
      }
    });
  });

  // ---------- bind to area ----------
  ['dragenter','dragover'].forEach(evt => {
    area.addEventListener(evt, (e) => {
      preventDefaults(e);
      if (e.dataTransfer) e.dataTransfer.dropEffect = 'copy';
      highlight();
    });
  });
  ['dragleave','dragend'].forEach(evt => {
    area.addEventListener(evt, (e) => { preventDefaults(e); unhighlight(); });
  });

  area.addEventListener('drop', async (e) => {
    preventDefaults(e); unhighlight();
    const files = e.dataTransfer?.files;

    // 1) dropped real file
    if (files && files.length) { assignToInput(files[0]); return; }

    // 2) dropped URL (from other tab / browser)
    const url = getDroppedUrl(e);
    if (url) {
      try {
        const file = await fetchAsFile(url); // may fail due to CORS
        assignToInput(file);
      } catch {
        // CORS or other failure: preview via URL, keep message
        preview.src = url; preview.classList.remove('hidden');
        fileName.textContent = url;
        setError('Preview from URL. To upload, please save the image and drop the file (site blocks direct download).');
      }
    }
  });

  // Click & keyboard
  area.addEventListener('click', () => input.click());
  area.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); } });

  // Paste support (image or URL)
  area.addEventListener('paste', async (e) => {
    const items = e.clipboardData?.items || [];
    let handled = false;
    for (const it of items) {
      if (it.type && it.type.startsWith('image/')) {
        const f = it.getAsFile();
        if (f) { assignToInput(f); handled = true; break; }
      }
    }
    if (handled) return;
    const text = e.clipboardData?.getData('text/plain');
    if (text && /^https?:\/\//i.test(text.trim())) {
      try {
        const file = await fetchAsFile(text.trim());
        assignToInput(file);
      } catch {
        preview.src = text.trim(); preview.classList.remove('hidden');
        fileName.textContent = text.trim();
        setError('Preview from URL. To upload, please save the image and drop the file (site blocks direct download).');
      }
    }
  });

  // Native input change
  input.addEventListener('change', () => {
    const f = input.files?.[0];
    if (f) assignToInput(f);
  });

  // ---------- Slug / SKU ----------
  const slugInput = document.getElementById("slug");
  const skuInput  = document.getElementById("sku");
  const nameInput = document.getElementById("name");
  const slugify = (s) => s.toString().toLowerCase()
    .replace(/\s+/g,'-').replace(/[^\w\-]+/g,'')
    .replace(/\-\-+/g,'-').replace(/^-+| -+$/g,''); // keep as in your version

  nameInput.addEventListener("input", () => {
    const val = nameInput.value || '';
    if (slugInput && !slugInput.dataset.touched) slugInput.value = slugify(val);
    if (skuInput && !skuInput.value) {
      const base = val.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 8);
      const rand = Math.floor(Math.random()*1000).toString().padStart(3,'0');
      skuInput.value = base ? (base + rand) : '';
    }
  });
  if (slugInput) slugInput.addEventListener('input', () => { slugInput.dataset.touched = '1'; });

  // ---------- SEO generator (wired to your previous endpoint) ----------
  const btnSeo = document.getElementById("btn-generate-seo");
  if (btnSeo) {
    btnSeo.addEventListener('click', async () => {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const name = nameInput?.value?.trim();
      if (!name) { alert('Please enter the Product Name first.'); return; }

      const catSel = document.getElementById('category_id');
      const category = catSel && catSel.options[catSel.selectedIndex]?.text?.trim() || 'General';
      const description = document.getElementById('description')?.value?.trim() || '';
      const specifications = document.getElementById('specifications')?.value?.trim() || '';
      const features = [description, specifications].filter(Boolean).join("\n");

      btnSeo.disabled = true;
      const old = btnSeo.textContent;
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

        if (slugInput && data.slug && !slugInput.dataset.touched) slugInput.value = data.slug;
        if (metaTitle && data.meta_title) metaTitle.value = data.meta_title;
        if (metaDesc  && data.meta_description) metaDesc.value = data.meta_description;
        if (metaKeys  && data.meta_keywords) metaKeys.value = data.meta_keywords;
      } catch (e) {
        console.error(e);
        alert('Could not generate SEO. Please try again.');
      } finally {
        btnSeo.disabled = false;
        btnSeo.textContent = old;
      }
    });
  }
});
</script>
@endsection
