@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Supplier Purchases</h2>
        <div class="text-sm text-gray-500">Supplier: <span class="font-semibold">{{ $supplier->name }}</span></div>
      </div>
      <a href="{{ url()->previous() }}" class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold hover:bg-gray-200">← Back</a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
      <div class="md:col-span-2">
        <label class="text-xs text-gray-600">Search</label>
        <input type="text" name="q" value="{{ $q }}" placeholder="Product or Bill no."
               class="w-full rounded-xl border px-3 py-2">
      </div>
      <div>
        <label class="text-xs text-gray-600">From (BS)</label>
        <input
          type="text"
          name="from_bs"
          value="{{ $from_bs }}"
          class="w-full rounded-xl border px-3 py-2 date-mask"
          placeholder="YYYY-MM-DD"
          inputmode="numeric"
          maxlength="10"
          pattern="\d{4}-\d{2}-\d{2}"
          autocomplete="off"
          title="Use format YYYY-MM-DD">
      </div>
      <div>
        <label class="text-xs text-gray-600">To (BS)</label>
        <input
          type="text"
          name="to_bs"
          value="{{ $to_bs }}"
          class="w-full rounded-xl border px-3 py-2 date-mask"
          placeholder="YYYY-MM-DD"
          inputmode="numeric"
          maxlength="10"
          pattern="\d{4}-\d{2}-\d{2}"
          autocomplete="off"
          title="Use format YYYY-MM-DD">
      </div>
      <div class="flex items-end">
        <button class="w-full rounded-xl bg-gray-100 px-3 py-2 text-sm font-semibold hover:bg-gray-200">Filter</button>
      </div>
    </form>

    {{-- Quick totals --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Total Qty</div>
        <div class="text-2xl font-bold">{{ number_format($totals['sum_qty']) }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Purchase (excl. VAT)</div>
        <div class="text-xl font-semibold">Rs. {{ number_format($totals['sum_excl'],2) }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">VAT (13%)</div>
        <div class="text-xl font-semibold">Rs. {{ number_format($totals['sum_vat'],2) }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Total Purchase</div>
        <div class="text-xl font-semibold">Rs. {{ number_format($totals['sum_total'],2) }}</div>
      </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left">Date (BS)</th>
            <th class="px-4 py-3 text-left">Product</th>
            <th class="px-4 py-3 text-right">Qty</th>
            <th class="px-4 py-3 text-right">Rate excl. VAT</th>
            <th class="px-4 py-3 text-right">VAT (13%)</th>
            <th class="px-4 py-3 text-right">Rate incl. VAT</th>
            <th class="px-4 py-3 text-right">Total</th>
            <th class="px-4 py-3 text-left">Bill No</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($purchases as $row)
            <tr>
              <td class="px-4 py-3">
                {{optional($row->purchased_at)->format('Y-m-d') ?? '-' }}

              
              </td>
              <td class="px-4 py-3">
                {{ $row->product_name ?? optional($row->product)->name ?? '-' }}
              </td>
              <td class="px-4 py-3 text-right">{{ $row->quantity }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($row->rate_excl_vat,2) }}</td>
              <td class="px-4 py-3 text-right">
                @php
                  $vatPerUnit = max(0, ($row->rate_incl_vat - $row->rate_excl_vat));
                @endphp
                Rs. {{ number_format($vatPerUnit, 2) }}
              </td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($row->rate_incl_vat,2) }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($row->total,2) }}</td>
              <td class="px-4 py-3">{{ $row->bill_no ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="8" class="px-4 py-6 text-center text-gray-500">No purchases found.</td></tr>
          @endforelse
        </tbody>

        {{-- Totals footer --}}
        <tfoot class="bg-gray-50">
          <tr>
            <td class="px-4 py-3 font-semibold" colspan="2">Totals</td>
            <td class="px-4 py-3 text-right font-semibold">{{ number_format($totals['sum_qty']) }}</td>
            <td class="px-4 py-3 text-right font-semibold">Rs. {{ number_format($totals['sum_excl'],2) }}</td>
            <td class="px-4 py-3 text-right font-semibold">Rs. {{ number_format($totals['sum_vat'],2) }}</td>
            <td class="px-4 py-3"></td>
            <td class="px-4 py-3 text-right font-semibold">Rs. {{ number_format($totals['sum_total'],2) }}</td>
            <td class="px-4 py-3"></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="mt-4">{{ $purchases->links() }}</div>
  </div>
</section>

{{-- Auto format YYYY-MM-DD (insert "-" immediately after 4th and 6th digits, digits only) --}}
<script>
  (function () {
    function toMasked(value) {
      let d = (value || '').replace(/\D/g, '').slice(0, 8); // YYYYMMDD
      const y = d.slice(0,4), m = d.slice(4,6), day = d.slice(6,8);

      if (d.length === 0) return '';
      if (d.length < 4)   return d;
      if (d.length === 4) return y + '-';
      if (d.length < 6)   return y + '-' + m;
      if (d.length === 6) return y + '-' + m + '-';
      return y + '-' + m + '-' + day;
    }

    function attachMask(el) {
      el.addEventListener('keypress', e => { if (!/[0-9]/.test(e.key)) e.preventDefault(); });
      el.addEventListener('input', () => {
        const masked = toMasked(el.value);
        if (el.value !== masked) el.value = masked;
        const len = el.value.length;
        el.setSelectionRange(len, len);
      });
      el.addEventListener('blur', () => { el.value = toMasked(el.value); });
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.date-mask').forEach(attachMask);
    });
  })();
</script>
@endsection
