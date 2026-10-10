{{-- resources/views/backend/credits/show.blade.php --}}
@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $credit->party_name }}</h2>
        <div class="text-sm text-gray-500">{{ $credit->contact ?? '-' }} • {{ $credit->address ?? '-' }}</div>
      </div>
      <a href="{{ route('credits.index') }}" class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold">← Back</a>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-xl bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
      <div class="mb-4 rounded-xl bg-red-50 p-3 text-red-700">
        <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Principal</div>
        <div class="text-2xl font-bold">Rs. {{ number_format($credit->principal_amount,2) }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Outstanding</div>
        <div class="text-2xl font-bold {{ $credit->outstanding_amount > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
          Rs. {{ number_format($credit->outstanding_amount,2) }}
        </div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Credit Date</div>
        <div class="text-lg font-semibold">{{ $credit->credit_date_bs }} <span class="text-gray-400"> ({{ optional($credit->credit_date_ad)->format('Y-m-d') }})</span></div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Due</div>
        <div class="text-lg font-semibold">{{ $credit->due_date_bs ?? '-' }} @if($credit->due_date_ad) <span class="text-gray-400"> ({{ $credit->due_date_ad->format('Y-m-d') }})</span>@endif</div>
      </div>
    </div>

    {{-- Add collection --}}
    @if($credit->status === 'open')
      <div class="rounded-2xl border bg-white shadow-sm p-5 mb-8">
        <h3 class="text-lg font-semibold mb-4">Add Collection</h3>
        <form action="{{ route('credits.payment', $credit) }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4">
          @csrf
          <div class="md:col-span-3">
            <label class="text-sm">Amount (Rs.) *</label>
            <input type="number" step="0.01" min="0.01" name="amount" required class="w-full rounded-xl border px-3 py-2">
          </div>
          <div class="md:col-span-3">
            <label class="text-sm">Paid Date (BS) *</label>
            <input
              type="text"
              id="paid_date_bs"
              name="paid_date_bs"
              required
              class="w-full rounded-xl border px-3 py-2 date-mask"
              placeholder="YYYY-MM-DD"
              inputmode="numeric"
              maxlength="10"
              pattern="\d{4}-\d{2}-\d{2}"
              autocomplete="off"
              title="Use format YYYY-MM-DD">
            <input type="hidden" id="paid_date_ad" name="paid_date_ad">
          </div>
          <div class="md:col-span-3">
            <label class="text-sm">Reference</label>
            <input type="text" name="reference" class="w-full rounded-xl border px-3 py-2" placeholder="Receipt no.">
          </div>
          <div class="md:col-span-3">
            <label class="text-sm">Remarks</label>
            <input type="text" name="remarks" class="w-full rounded-xl border px-3 py-2">
          </div>
          <div class="md:col-span-12 flex justify-end">
            <button class="rounded-xl bg-emerald-600 px-4 py-2 text-white font-semibold">Add</button>
          </div>
        </form>
      </div>
    @endif

    {{-- Payment history --}}
    <div class="rounded-2xl border bg-white shadow-sm">
      <div class="px-5 py-4 border-b bg-gray-50">
        <h3 class="text-lg font-semibold">Collections</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50 text-xs uppercase text-gray-600">
            <tr>
              <th class="px-4 py-3 text-left">Date (BS)</th>
              <th class="px-4 py-3 text-left">Date (AD)</th>
              <th class="px-4 py-3 text-right">Amount</th>
              <th class="px-4 py-3 text-left">Reference</th>
              <th class="px-4 py-3 text-left">Remarks</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($credit->payments as $p)
              <tr>
                <td class="px-4 py-3">{{ $p->paid_date_bs }}</td>
                <td class="px-4 py-3">{{ optional($p->paid_date_ad)->format('Y-m-d') }}</td>
                <td class="px-4 py-3 text-right">Rs. {{ number_format($p->amount,2) }}</td>
                <td class="px-4 py-3">{{ $p->reference ?? '-' }}</td>
                <td class="px-4 py-3">{{ $p->remarks ?? '-' }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No collections yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Close credit --}}
    @if($credit->status === 'open' && (float)$credit->outstanding_amount <= 0)
      <form action="{{ route('credits.close', $credit) }}" method="POST" class="mt-4 flex justify-end">
        @csrf
        <button class="rounded-xl bg-gray-800 px-4 py-2 text-white text-sm font-semibold">Close Credit</button>
      </form>
    @endif
  </div>
</section>

{{-- Nepali Datepicker (optional, BS only) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nepali-datepicker-js@3.7.0/dist/nepali.datepicker.v3.7.min.css" />
<script src="https://cdn.jsdelivr.net/npm/nepali-datepicker-js@3.7.0/dist/nepali.datepicker.v3.7.min.js"></script>

{{-- Auto format YYYY-MM-DD: insert "-" immediately after 4th and 6th digits, allow digits only --}}
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
      const paidBs = document.getElementById('paid_date_bs');
      paidBs && attachMask(paidBs);

      // Initialize Nepali datepicker if present; sync to mask on change
      if (window.nepaliDatePicker && paidBs) {
        nepaliDatePicker(paidBs, { ndpYear: true, ndpMonth: true, ndpYearCount: 30 });
        paidBs.addEventListener('change', () => setTimeout(() => { paidBs.value = toMasked(paidBs.value); }, 0));
      }
    });
  })();
</script>
@endsection
