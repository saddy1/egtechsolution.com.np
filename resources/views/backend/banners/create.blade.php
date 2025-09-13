@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-5xl">

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h5 class="text-2xl font-bold tracking-tight text-gray-900">Create Banner</h5>
      <a href="{{ route('banner.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 transition hover:bg-gray-200">
        ← Back to Banners
      </a>
    </div>

    {{-- Card --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="border-b bg-gray-50 px-5 py-4">
        <p class="text-sm text-gray-600">Upload a banner image and optional link. Fields marked * are required.</p>
      </div>

      <div class="px-5 py-6">
        {{-- Errors --}}
        @if ($errors->any())
          <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
              @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-6">
          @csrf

          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-6">
              <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Name *</label>
              <input type="text" name="name" id="name" value="{{ old('name') }}" required
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-6">
              <label for="link_url" class="mb-1 block text-sm font-medium text-gray-700">Link URL (optional)</label>
              <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" placeholder="https://example.com"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('link_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-6">
              <label for="alt_text" class="mb-1 block text-sm font-medium text-gray-700">Alt text</label>
              <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text') }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('alt_text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3">
              <label for="sort_order" class="mb-1 block text-sm font-medium text-gray-700">Sort order</label>
              <input type="number" name="sort_order" id="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                     class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3 flex items-end">
              <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                       {{ old('is_active', 1) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Active</span>
              </label>
            </div>
          </div>

          {{-- Image uploader --}}
          <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <div class="md:col-span-12">
              <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Image *</label>

              <input type="file" name="image" id="image" accept="image/*" class="sr-only" required>

              <div id="drop-area"
                   class="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center transition hover:border-indigo-400 hover:bg-indigo-50">
                <div class="rounded-xl bg-white p-3 shadow-sm ring-1 ring-gray-200">
                  <svg class="h-6 w-6 text-gray-600" viewBox="0 0 24 24" fill="none">
                    <path d="M12 16V8m0 0l-3 3m3-3 3 3M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-700">Drag & drop an image here, or <span class="text-indigo-600 underline">browse</span></p>
                <p class="text-xs text-gray-500">JPG, PNG, WEBP up to ~5MB</p>
                <p id="file-name" class="mt-1 text-xs font-medium text-gray-600"></p>

                <img id="preview" class="mt-4 hidden max-h-64 rounded-xl object-contain ring-1 ring-gray-200" alt="Preview">
              </div>

              @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
            <a href="{{ route('banner.index') }}"
               class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-800 ring-1 ring-gray-200 transition hover:bg-gray-200">
              Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
              Create
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</section>

{{-- Drag & drop + preview --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const area = document.getElementById('drop-area');
  const input = document.getElementById('image');
  const nameEl = document.getElementById('file-name');
  const preview = document.getElementById('preview');

  const show = f => {
    nameEl.textContent = f ? f.name : '';
    if (f && f.type.startsWith('image/')) {
      const r = new FileReader();
      r.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
      r.readAsDataURL(f);
    } else { preview.classList.add('hidden'); preview.src = ''; }
  };

  area.addEventListener('click', () => input.click());
  area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('border-indigo-500','bg-indigo-50'); });
  area.addEventListener('dragleave', () => area.classList.remove('border-indigo-500','bg-indigo-50'));
  area.addEventListener('drop', e => {
    e.preventDefault(); area.classList.remove('border-indigo-500','bg-indigo-50');
    if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; show(input.files[0]); }
  });
  input.addEventListener('change', () => show(input.files?.[0]));
});
</script>
@endsection
