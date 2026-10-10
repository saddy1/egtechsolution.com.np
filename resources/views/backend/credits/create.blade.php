{{-- resources/views/backend/credits/create.blade.php --}}
@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6 max-w-3xl mx-auto">
  <div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold">New Credit</h2>
    <a href="{{ route('credits.index') }}" class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold">← Back</a>
  </div>

  @if ($errors->any())
    <div class="mb-4 rounded-xl bg-red-50 p-3 text-red-700">
      <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('credits.store') }}" method="POST" class="space-y-5">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="text-sm">Name *</label>
        <input type="text" name="party_name" required class="w-full rounded-xl border px-3 py-2">
      </div>
      <div>
        <label class="text-sm">Contact</label>
        <input type="text" name="contact" class="w-full rounded-xl border px-3 py-2">
      </div>
      <div class="md:col-span-2">
        <label class="text-sm">Address</label>
        <input type="text" name="address" class="w-full rounded-xl border px-3 py-2">
      </div>
    </div>

    <div>
      <label class="text-sm">Details</label>
      <input type="text" name="details" class="w-full rounded-xl border px-3 py-2" placeholder="Purpose / brief">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="text-sm">Principal Amount (Rs.) *</label>
        <input type="number" name="principal_amount" step="0.01" min="0" required class="w-full rounded-xl border px-3 py-2">
      </div>

      <div>
        <label class="text-sm">Credit Date (BS) *</label>
        <input
          type="text"
          id="credit_date_bs"
          name="credit_date_bs"
          required
          class="w-full rounded-xl border px-3 py-2 date-mask"
          placeholder="YYYY-MM-DD"
          inputmode="numeric"
          maxlength="10"
          pattern="\d{4}-\d{2}-\d{2}"
          autocomplete="off"
          title="Use format YYYY-MM-DD">
      </div>

      <div>
        <label class="text-sm">Due Date (BS)</label>
        <input
          type="text"
          id="due_date_bs"
          name="due_date_bs"
          class="w-full rounded-xl border px-3 py-2 date-mask"
          placeholder="YYYY-MM-DD"
          inputmode="numeric"
          maxlength="10"
          pattern="\d{4}-\d{2}-\d{2}"
          autocomplete="off"
          title="Use format YYYY-MM-DD">
      </div>
    </div>

    <div>
      <label class="text-sm">Notes</label>
      <textarea name="notes" rows="2" class="w-full rounded-xl border px-3 py-2"></textarea>
    </div>

    <div class="flex justify-end gap-2">
      <button class="rounded-xl bg-indigo-600 px-4 py-2 text-white font-semibold">Save</button>
    </div>
  </form>
</section>

{{-- Nepali Datepicker (BS only) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nepali-datepicker-js@3.7.0/dist/nepali.datepicker.v3.7.min.css" />
<script src="https://cdn.jsdelivr.net/npm/nepali-datepicker-js@3.7.0/dist/nepali.datepicker.v3.7.min.js"></script>

{{-- Auto format YYYY-MM-DD: insert "-" immediately after 4th and 6th digits, allow digits only --}}
<script>
  (function () {
    function toMasked(value) {
      // Keep only digits, max 8 (YYYYMMDD)
      let d = (value || '').replace(/\D/g, '').slice(0, 8);
      const y = d.slice(0,4), m = d.slice(4,6), day = d.slice(6,8);

      if (d.length === 0) return '';
      if (d.length < 4)   return d;
      if (d.length === 4) return y + '-';                 // trailing dash right away
      if (d.length < 6)   return y + '-' + m;
      if (d.length === 6) return y + '-' + m + '-';       // trailing dash right away
      return y + '-' + m + '-' + day;
    }

    function attachMask(el) {
      // block non-digit keypresses
      el.addEventListener('keypress', e => {
        if (!/[0-9]/.test(e.key)) e.preventDefault();
      });

      // handle typing & paste
      el.addEventListener('input', () => {
        const masked = toMasked(el.value);
        if (el.value !== masked) {
          el.value = masked;
        }
        // keep caret at end for simplicity and good UX
        const len = el.value.length;
        el.setSelectionRange(len, len);
      });

      // normalize on blur
      el.addEventListener('blur', () => {
        el.value = toMasked(el.value);
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      const credit = document.getElementById('credit_date_bs');
      const due    = document.getElementById('due_date_bs');
      [credit, due].forEach(el => el && attachMask(el));

      // init Nepali datepicker (it sets full date string; mask won’t fight it)
      if (window.nepaliDatePicker) {
        nepaliDatePicker(credit, { ndpYear: true, ndpMonth: true, ndpYearCount: 30 });
        nepaliDatePicker(due,    { ndpYear: true, ndpMonth: true, ndpYearCount: 30 });

        // When datepicker chooses a date, ensure it conforms to mask (in case of different separators)
        const syncMask = (el) => {
          if (!el) return;
          // Trigger a small async to capture picker-written value
          setTimeout(() => { el.value = toMasked(el.value); }, 0);
        };
        credit && credit.addEventListener('change', () => syncMask(credit));
        due && due.addEventListener('change', () => syncMask(due));
      }
    });
  })();
</script>
@endsection
