@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h2 class="text-2xl font-bold text-gray-900">📊 Summary — Purchase & Sales</h2>
      <a href="{{ route('stock.stock.index') }}" class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold hover:bg-gray-200">← Back</a>
    </div>

    {{-- Filters (BS) --}}
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
        <a href="{{ route('stock.summary') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Clear</a>
      @endif
    </form>

    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left">Type</th>
            <th class="px-4 py-3 text-right">Total (Excl. VAT)</th>
            <th class="px-4 py-3 text-right">VAT ({{ $vatRatePercent }}%)</th>
            <th class="px-4 py-3 text-right">Total (Incl. VAT)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          {{-- Purchases --}}
          <tr>
            <td class="px-4 py-3 font-medium">Purchases</td>
            <td class="px-4 py-3 text-right">Rs. {{ number_format($totals['purchase_excl'],2) }}</td>
            <td class="px-4 py-3 text-right">Rs. {{ number_format($totals['purchase_vat'],2) }}</td>
            <td class="px-4 py-3 text-right">Rs. {{ number_format($totals['purchase_incl'],2) }}</td>
          </tr>

          {{-- Sales --}}
          <tr>
            <td class="px-4 py-3 font-medium">Sales</td>
            <td class="px-4 py-3 text-right">Rs. {{ number_format($totals['sales_excl'],2) }}</td>
            <td class="px-4 py-3 text-right">Rs. {{ number_format($totals['sales_vat'],2) }}</td>
            <td class="px-4 py-3 text-right">Rs. {{ number_format($totals['sales_incl'],2) }}</td>
          </tr>
        </tbody>

        {{-- NET ROW (Sales − Purchases) --}}
        @php
          $netEx   = $totals['net_excl'];
          $netVat  = $totals['net_vat'];
          $netIncl = $totals['net_incl'];
          $neg = 'text-red-700';
          $pos = 'text-emerald-700';
        @endphp
        <tfoot class="bg-gray-50">
          <tr class="font-semibold">
            <td class="px-4 py-3 text-left">Net (Sales − Purchases)</td>
            <td class="px-4 py-3 text-right {{ $netEx < 0 ? $neg : $pos }}">
              {{ $netEx < 0 ? '−' : '' }}Rs. {{ number_format(abs($netEx),2) }}
            </td>
            <td class="px-4 py-3 text-right {{ $netVat < 0 ? $neg : $pos }}">
              {{ $netVat < 0 ? '−' : '' }}Rs. {{ number_format(abs($netVat),2) }}
            </td>
            <td class="px-4 py-3 text-right {{ $netIncl < 0 ? $neg : $pos }}">
              {{ $netIncl < 0 ? '−' : '' }}Rs. {{ number_format(abs($netIncl),2) }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

    {{-- tiny note about VAT interpretation --}}
    <p class="mt-3 text-xs text-gray-500">
      Note: Sales totals are calculated assuming selling prices {{ $pricesIncludeVat ? 'include' : 'exclude' }} VAT.
      Update this behavior in <code>StockController@summary</code>.
    </p>
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
