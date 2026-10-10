@extends('backend.layout.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <h1 class="text-2xl font-bold text-gray-900">Orders</h1>

    <form method="GET" class="w-full sm:w-auto">
      <div class="flex flex-col sm:flex-row gap-2">
        <input type="text" name="q" value="{{ $q }}"
               placeholder="Search id/name/email/phone"
               class="w-full sm:w-64 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
        <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
          <option value="">All Status</option>
          @foreach (['Pending','Verified','Shipped','Delivered'] as $s)
            <option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>
          @endforeach
        </select>
        <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
          Filter
        </button>
      </div>
    </form>
  </div>

  @if(session('status'))
    <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-2 text-emerald-800 text-sm ring-1 ring-emerald-200">
      {{ session('status') }}
    </div>
  @endif

  <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-600 uppercase text-xs">
        <tr>
          <th class="px-4 py-3 text-left">Order</th>
          <th class="px-4 py-3 text-left">Customer</th>
          <th class="px-4 py-3 text-left">Contact</th>
          <th class="px-4 py-3 text-left">Delivery To</th>
          <th class="px-4 py-3 text-left">Payment</th>
          <th class="px-4 py-3 text-left">Items</th>
          <th class="px-4 py-3 text-left">Total</th>
          <th class="px-4 py-3 text-left">Status</th>
          <th class="px-4 py-3 text-left">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($orders as $order)
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">
              <div class="font-semibold text-slate-900">#{{ $order->id }}</div>
              <div class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, h:i A') }}</div>
            </td>

            <td class="px-4 py-3">
              <div class="font-medium text-slate-900">{{ $order->name }}</div>
              <div class="text-xs text-slate-500">{{ $order->email }}</div>
            </td>

            <td class="px-4 py-3">
              <div class="text-slate-800">{{ $order->phone }}</div>
            </td>

            <td class="px-4 py-3">
              <div class="text-slate-800">{{ $order->city }}, {{ $order->country }}</div>
              <div class="text-xs text-slate-500 truncate max-w-[220px]" title="{{ $order->address }}">
                {{ $order->address }}
              </div>
            </td>

            <td class="px-4 py-3 capitalize">{{ $order->payment_method }}</td>

            <td class="px-4 py-3">{{ $order->items_count }}</td>

            <td class="px-4 py-3 font-semibold text-slate-900">Rs.{{ number_format($order->total, 2) }}</td>

            <td class="px-4 py-3">
              @php $st = strtolower($order->order_status); @endphp
              <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                @class([
                  'bg-yellow-100 text-yellow-800' => $st==='pending',
                  'bg-blue-100 text-blue-800'     => $st==='verified',
                  'bg-indigo-100 text-indigo-800' => $st==='shipped',
                  'bg-emerald-100 text-emerald-800'=> $st==='delivered',
                  'bg-slate-100 text-slate-800'    => !in_array($st,['pending','verified','shipped','delivered'])
                ])">
                {{ ucfirst($order->order_status) }}
              </span>
            </td>

            <td class="px-4 py-3">
              <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                   class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-800 hover:bg-slate-200">
                  View
                </a>

                <form action="{{ route('admin.orders.delete', $order->id) }}" method="POST"
                      onsubmit="return confirm('Delete order #{{ $order->id }}?');">
                  @csrf @method('DELETE')
                  <button class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="px-4 py-10 text-center text-slate-500">
              No orders found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $orders->links() }}
  </div>
</div>
@endsection
