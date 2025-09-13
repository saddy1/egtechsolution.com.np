@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-7xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h5 class="text-2xl font-bold tracking-tight text-gray-900">Banners</h5>
      <a href="{{ route('banner.create') }}"
         class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
        + Add Banner
      </a>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
        {{ session('success') }}
      </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-left text-gray-600">
            <tr>
              <th class="px-4 py-3 font-medium">#</th>
              <th class="px-4 py-3 font-medium">Preview</th>
              <th class="px-4 py-3 font-medium">Name</th>
              <th class="px-4 py-3 font-medium">Link</th>
              <th class="px-4 py-3 font-medium">Active</th>
              <th class="px-4 py-3 font-medium">Sort</th>
              <th class="px-4 py-3 font-medium">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            @forelse ($banners as $b)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-700">{{ $b->id }}</td>
                <td class="px-4 py-3">
                  <img src="{{ Storage::url($b->image_path) }}" alt="{{ $b->alt_text ?? $b->name }}"
                       class="h-16 w-40 rounded-lg object-cover ring-1 ring-gray-200" />
                </td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $b->name }}</td>
                <td class="px-4 py-3 max-w-[260px]">
                  @if($b->link_url)
                    <a href="{{ $b->link_url }}" class="truncate text-indigo-600 hover:underline block" target="_blank">{{ $b->link_url }}</a>
                  @else
                    <span class="text-gray-400">—</span>
                  @endif
                </td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                    {{ $b->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ $b->is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $b->sort_order }}</td>
                <td class="px-4 py-3">
                  <form action="{{ route('banner.destroy', $b) }}" method="POST"
                        onsubmit="return confirm('Delete this banner?')" class="inline">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-700">
                      Delete
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">No banners yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
        <div class="flex justify-center">
          {{ $banners->onEachSide(1)->links('pagination::tailwind') }}
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
