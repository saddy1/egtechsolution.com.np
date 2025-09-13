@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6 max-w-3xl mx-auto">
  <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Category</h2>

  <form action="{{ route('categories.update', $category) }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-6">
    @csrf
    @method('PUT')

    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
      <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
             class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
      @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2">
      <input type="checkbox" id="is_active" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}
             class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
      <label for="is_active" class="text-sm text-gray-700">Active</label>
    </div>

    <div class="flex justify-end">
      <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200">Cancel</a>
      <button type="submit" class="ml-3 px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700">Update</button>
    </div>
  </form>
</section>
@endsection
