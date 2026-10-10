@extends('backend.layout.app')
@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h2 class="text-2xl font-bold text-gray-900">     👥
        Users Management</h2>
      <a href="{{ route('admin.dashboard') }}" class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold hover:bg-gray-200">← Back</a>
    </div>
    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full
        divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left">Name</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-left">Role</th>
            <th class="px-4 py-3 text-left">Created At</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach ($users as $user)
          <tr>
            <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
            <td class="px-4 py-3">{{ $user->email }}</td>
            <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
            <td class="px-4 py-3">{{ $user->created_at->format('d M Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection