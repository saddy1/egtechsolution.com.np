@php
  $invNo = 'INV-'.now()->format('Ymd').'-'.$order->id;
  $placed = ($order->placed_at ?? $order->created_at)->format('d M Y, h:i A');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $invNo }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  @vite('resources/css/app.css')
  <style>
    @media print {
      .no-print { display:none !important; }
      html, body { background: #fff; }
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800">
  <div class="max-w-4xl mx-auto my-6 bg-white rounded-2xl shadow p-6 ring-1 ring-slate-200">
    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-xl font-extrabold text-slate-900">EG Tech Solution</h1>
        <p class="text-xs text-slate-500">Invoice / Tax Receipt</p>
        <p class="mt-2 text-xs text-slate-500">www.egtechsolution.com.np</p>
      </div>
      <div class="text-right">
        <div class="text-sm font-semibold">Invoice #: {{ $invNo }}</div>
        <div class="text-xs text-slate-600">Date: {{ $placed }}</div>
        <div class="text-xs text-slate-600">Order ID: #{{ $order->id }}</div>
      </div>
    </div>

    <hr class="my-5">

    {{-- Addresses --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <h3 class="text-sm font-semibold text-slate-700">Bill To</h3>
        <div class="mt-1 text-sm">
          <div class="font-semibold">{{ $order->name }}</div>
          <div>{{ $order->address }}</div>
          <div>{{ $order->city }}, {{ $order->country }} {{ $order->zip }}</div>
          <div class="mt-1 text-slate-600 text-xs">📞 {{ $order->phone }} • 📧 {{ $order->email }}</div>
        </div>
      </div>
      <div>
        <h3 class="text-sm font-semibold text-slate-700">Payment</h3>
        <div class="mt-1 text-sm">
          <div>Method: <span class="font-semibold uppercase">{{ $order->payment_method }}</span></div>
          <div>Status: <span class="font-semibold capitalize">{{ $order->order_status }}</span></div>
        </div>
      </div>
    </div>

    {{-- Items --}}
    <div class="mt-6 overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-700">
          <tr>
            <th class="px-3 py-2 text-left">Product</th>
            <th class="px-3 py-2 text-left">Qty</th>
            <th class="px-3 py-2 text-left">Rate (Rs.)</th>
            <th class="px-3 py-2 text-left">Line Total (Rs.)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @foreach($order->items as $item)
            <tr>
              <td class="px-3 py-2">
                <div class="font-semibold text-slate-900">{{ $item->product_name }}</div>
                <div class="text-xs text-slate-500">#{{ $item->product_id }}</div>
              </td>
              <td class="px-3 py-2">{{ $item->quantity }}</td>
              <td class="px-3 py-2">{{ number_format($item->price, 2) }}</td>
              <td class="px-3 py-2 font-semibold">{{ number_format($item->total, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-slate-50">
          <tr>
            <td colspan="3" class="px-3 py-2 text-right text-slate-600">Subtotal</td>
            <td class="px-3 py-2 font-semibold">
              Rs.{{ number_format(($order->subtotal ?? $order->total), 2) }}
            </td>
          </tr>
          {{-- If you later add taxes / shipping, show here --}}
          <tr>
            <td colspan="3" class="px-3 py-2 text-right text-slate-900 font-bold">Total</td>
            <td class="px-3 py-2 font-extrabold text-slate-900">
              Rs.{{ number_format($order->total, 2) }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

    {{-- Notes --}}
    <div class="mt-6 text-xs text-slate-500">
      <p>Thank you for your purchase! This is a system-generated invoice. For support, contact info@egtechsolution.com.np</p>
    </div>

    {{-- Actions --}}
    <div class="mt-6 flex items-center gap-2 no-print">
      <button onclick="window.print()"
              class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-200">
        Print
      </button>
      @if(class_exists(\Barryvdh\DomPDF\ServiceProvider::class))
        <a href="{{ route('admin.orders.invoice.pdf', $order->id) }}"
           class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
          Download PDF
        </a>
      @endif
      <a href="{{ route('admin.orders.show', $order->id) }}"
         class="ml-auto rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-200">
        Back to Order
      </a>
    </div>
  </div>
</body>
</html>
