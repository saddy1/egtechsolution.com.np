@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-5xl">

    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
      <a href="{{ route('categories.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
        + Add Category
      </a>
    </div>

    @if(session('success'))
      <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">
        {{ session('success') }}
      </div>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded-lg">
      <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
          <tr>
            <th class="px-4 py-3">#</th>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories as $i => $row)
            <tr class="border-t hover:bg-gray-50">
              <td class="px-4 py-3">{{ $categories->firstItem() + $i }}</td>
              <td class="px-4 py-3 font-semibold">{{ $row->name }}</td>
              <td class="px-4 py-3">
                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $row->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                  {{ $row->is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-4 py-3 space-x-2">
                <a href="{{ route('categories.edit', $row) }}" class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('categories.destroy', $row) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('Delete this category?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $categories->links('pagination::tailwind') }}
    </div>
  </div>
</section>
@endsection
