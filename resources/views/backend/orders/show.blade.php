@extends('backend.layout.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 ">
    <a href="{{ route('admin.orders.invoice.html', $order->id) }}"
   class="rounded-lg bg-green-500 px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-200 gap-2 inline-flex items-center no-print ">
  View Invoice
</a>

<a href="{{ route('admin.orders.invoice.pdf', $order->id) }}"
   class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
  Download PDF
</a>
</div>

  <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
      <p class="text-sm text-slate-600">
        Placed: {{ optional($order->placed_at ?? $order->created_at)->format('d M Y, h:i A') }}
        @if($order->user)
          • Customer account: <span class="font-medium">{{ $order->user->name }}</span> (ID: {{ $order->user->id }})
        @endif
      </p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.orders.index') }}"
         class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-200">← Back</a>
      <form action="{{ route('admin.orders.delete', $order->id) }}" method="POST"
            onsubmit="return confirm('Delete this order?');">
        @csrf @method('DELETE')
        <button class="rounded-lg bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100">
          Delete
        </button>
      </form>
    </div>
  </div>

  @if(session('status'))
    <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-2 text-emerald-800 text-sm ring-1 ring-emerald-200">
      {{ session('status') }}
    </div>
  @endif

  {{-- Top summary cards --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <h3 class="text-sm font-semibold text-slate-600 mb-2">Customer</h3>
      <div class="text-sm text-slate-800">
        <div class="font-semibold">{{ $order->name }}</div>
        <div class="mt-1">📧 {{ $order->email }}</div>
        <div>📞 {{ $order->phone }}</div>
      </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <h3 class="text-sm font-semibold text-slate-600 mb-2">Delivery Address</h3>
      <div class="text-sm text-slate-800">
        <div class="font-semibold">{{ $order->address }}</div>
        <div class="mt-1">{{ $order->city }}, {{ $order->country }} {{ $order->zip }}</div>
        <a class="mt-2 inline-block text-xs text-indigo-600 hover:text-indigo-700 underline"
           target="_blank"
           href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->address.' '.$order->city.' '.$order->country.' '.$order->zip) }}">
          View on Google Maps
        </a>
      </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <h3 class="text-sm font-semibold text-slate-600 mb-2">Payment & Status</h3>
      <div class="text-sm text-slate-800">
        <div>Payment: <span class="font-semibold uppercase">{{ $order->payment_method }}</span></div>
        <div class="mt-1">
          Order Status:
          <span class="ml-1 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
            @class([
              'bg-yellow-100 text-yellow-800' => strtolower($order->order_status) === 'pending',
              'bg-blue-100 text-blue-800'     => strtolower($order->order_status) === 'confirmed',
              'bg-indigo-100 text-indigo-800' => strtolower($order->order_status) === 'shipped',
              'bg-emerald-100 text-emerald-800' => strtolower($order->order_status) === 'delivered',
              'bg-slate-100 text-slate-800'   => !in_array(strtolower($order->order_status), ['pending','confirmed','shipped','delivered']),
            ])">
            {{ ucfirst($order->order_status) }}
          </span>
        </div>
        <div class="mt-2">
          <div>Subtotal: <span class="font-semibold">Rs.{{ number_format($order->subtotal ?? $order->total, 2) }}</span></div>
          <div>Total: <span class="font-bold">Rs.{{ number_format($order->total, 2) }}</span></div>
        </div>
      </div>
    </div>
  </div>

  {{-- Items table --}}
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="border-b bg-slate-50 p-4">
      <h3 class="text-base font-semibold text-slate-900">Ordered Items ({{ $order->items->count() }})</h3>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">Product</th>
            <th class="px-4 py-3 text-left">Qty</th>
            <th class="px-4 py-3 text-left">Rate</th>
            <th class="px-4 py-3 text-left">Total</th>
            <th class="px-4 py-3 text-left">Item Status</th>
            <th class="px-4 py-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @foreach($order->items as $item)
            <tr class="hover:bg-slate-50">
              <td class="px-4 py-3">
                <div class="font-semibold text-slate-900">{{ $item->product_name }}</div>
                @if($item->product)
                  <div class="text-xs text-slate-500">#{{ $item->product_id }}</div>
                @endif
              </td>
              <td class="px-4 py-3">{{ $item->quantity }}</td>
              <td class="px-4 py-3">Rs.{{ number_format($item->price, 2) }}</td>
              <td class="px-4 py-3 font-semibold text-slate-900">Rs.{{ number_format($item->total, 2) }}</td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold
                  @class([
                    'bg-yellow-100 text-yellow-800' => strtolower($item->status) === 'pending',
                    'bg-blue-100 text-blue-800'     => strtolower($item->status) === 'verified',
                    'bg-indigo-100 text-indigo-800' => strtolower($item->status) === 'shipped',
                    'bg-emerald-100 text-emerald-800' => strtolower($item->status) === 'delivered',
                    'bg-slate-100 text-slate-800'   => !in_array(strtolower($item->status), ['pending','verified','shipped','delivered']),
                  ])">
                  {{ ucfirst($item->status) }}
                </span>
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-wrap gap-2">
                  @if(strtolower($item->status) === 'pending')
                    <form action="{{ route('admin.orders.verify', $item->id) }}" method="POST">@csrf
                      <button class="rounded-lg bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800 hover:bg-yellow-200">Verify</button>
                    </form>
                  @elseif(strtolower($item->status) === 'verified')
                    <form action="{{ route('admin.orders.ship', $item->id) }}" method="POST">@csrf
                      <button class="rounded-lg bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 hover:bg-blue-200">Ship</button>
                    </form>
                  @elseif(strtolower($item->status) === 'shipped')
                    <form action="{{ route('admin.orders.deliver', $item->id) }}" method="POST">@csrf
                      <button class="rounded-lg bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 hover:bg-emerald-200">Deliver</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-slate-50">
          <tr>
            <td colspan="6" class="px-4 py-3 text-right text-sm text-slate-700">
              <span class="mr-4">Subtotal: <strong>Rs.{{ number_format($order->subtotal ?? $order->total, 2) }}</strong></span>
              <span>Total: <strong>Rs.{{ number_format($order->total, 2) }}</strong></span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

</div>
@endsection
