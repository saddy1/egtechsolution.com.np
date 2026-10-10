@extends('backend.layout.app')
@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h2 class="text-2xl font-bold">Profit & Loss</h2>
      <a href="{{ route('billing.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-white text-sm font-semibold">+ New Bill</a>
    </div>

    <form method="GET" class="mb-4 flex flex-wrap items-end gap-3">
      <div>
        <label class="text-xs text-gray-600">From (BS)</label>
        <input
          type="text"
          name="from_bs"
          value="{{ $from_bs }}"
          class="rounded-xl border px-3 py-2 date-mask"
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
          class="rounded-xl border px-3 py-2 date-mask"
          placeholder="YYYY-MM-DD"
          inputmode="numeric"
          maxlength="10"
          pattern="\d{4}-\d{2}-\d{2}"
          autocomplete="off"
          title="Use format YYYY-MM-DD">
      </div>
      <button class="rounded-xl bg-gray-100 px-3 py-2 text-sm font-semibold">Filter</button>
      @if($from_bs || $to_bs)
        <a href="{{ route('billing.profit') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Clear</a>
      @endif
    </form>

    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left">Product</th>
            <th class="px-4 py-3 text-right">Qty</th>
            <th class="px-4 py-3 text-right">Cost Price</th>
            <th class="px-4 py-3 text-right">Selling Price</th>
            <th class="px-4 py-3 text-right">Line Cost</th>
            <th class="px-4 py-3 text-right">Line Total</th>
            <th class="px-4 py-3 text-right">Profit/Loss</th>
            <th class="px-4 py-3 text-left">Bill No</th>
            <th class="px-4 py-3 text-left">Bill Date</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($items as $it)
            @php
              $isLoss = $it->line_profit < 0;
              $profitAbs = number_format(abs($it->line_profit), 2);
            @endphp
            <tr>
              <td class="px-4 py-3">{{ $it->product_name }}</td>
              <td class="px-4 py-3 text-right">{{ $it->quantity }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($it->cost_price,2) }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($it->selling_price,2) }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($it->line_cost_total,2) }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($it->line_total,2) }}</td>
              <td class="px-4 py-3 text-right {{ $isLoss ? 'text-red-700' : 'text-emerald-700' }}">
                {{ $isLoss ? '−' : '' }}Rs. {{ $profitAbs }}
              </td>
              <td class="px-4 py-3">{{ $it->sale?->bill_no }}</td>
              <td class="px-4 py-3">{{ $it->sale?->bill_date_bs }}</td>
            </tr>

          @empty
            <tr><td colspan="8" class="px-4 py-6 text-center text-gray-500">No data</td></tr>
          @endforelse
        </tbody>

        {{-- Net total row (profits minus losses) --}}
        <tfoot class="bg-gray-50">
          <tr class="font-semibold">
            <td class="px-4 py-3 text-right" colspan="4">Totals</td>
            <td class="px-4 py-3 text-right">Rs. {{ number_format($net['cost'],2) }}</td>
            <td class="px-4 py-3 text-right">Rs. {{ number_format($net['selling'],2) }}</td>

            @php
              $netVal = $net['net'];
              $netIsLoss = $netVal < 0;
              $netAbs = number_format(abs($netVal), 2);
            @endphp
            <td class="px-4 py-3 text-right {{ $netIsLoss ? 'text-red-700' : 'text-emerald-700' }}">
              {{ $netIsLoss ? '−' : '' }}Rs. {{ $netAbs }}
            </td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
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
