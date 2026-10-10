@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h2 class="text-2xl font-bold text-gray-900">Credits</h2>
      <a href="{{ route('credits.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-white text-sm font-semibold">+ New Credit</a>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-xl bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Filter Form --}}
    <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-6 gap-3">
      <div class="md:col-span-2">
        <label class="text-xs text-gray-600">Search</label>
        <input type="text" name="q" value="{{ $q }}" class="w-full rounded-xl border px-3 py-2" placeholder="Name / Contact / Address / Date (BS)">
      </div>
      <div>
        <label class="text-xs text-gray-600">Status</label>
        <select name="status" class="w-full rounded-xl border px-3 py-2">
          <option value="">All</option>
          <option value="open"   @selected($status==='open')>Open</option>
          <option value="closed" @selected($status==='closed')>Closed</option>
        </select>
      </div>
      <div>
        <label class="text-xs text-gray-600">From (BS)</label>
        <input
          type="text"
          id="from_bs"
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
          id="to_bs"
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

    {{-- quick totals --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Total Principal</div>
        <div class="text-2xl font-bold">Rs. {{ number_format($totals['principal'],2) }}</div>
      </div>
      <div class="rounded-xl border p-4">
        <div class="text-xs text-gray-500">Total Outstanding</div>
        <div class="text-2xl font-bold">Rs. {{ number_format($totals['outstanding'],2) }}</div>
      </div>
    </div>

    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left">Name</th>
            <th class="px-4 py-3 text-left">Contact</th>
            <th class="px-4 py-3 text-left">Address</th>
            <th class="px-4 py-3 text-right">Principal</th>
            <th class="px-4 py-3 text-right">Outstanding</th>
            <th class="px-4 py-3 text-left">Credit Date (BS)</th>
            <th class="px-4 py-3 text-left">Due (BS)</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-right">View</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($credits as $c)
            <tr class="{{ $c->status==='open' ? 'bg-white' : 'bg-gray-50' }}">
              <td class="px-4 py-3 font-medium">{{ $c->party_name }}</td>
              <td class="px-4 py-3">{{ $c->contact ?? '-' }}</td>
              <td class="px-4 py-3">{{ $c->address ?? '-' }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($c->principal_amount,2) }}</td>
              <td class="px-4 py-3 text-right">Rs. {{ number_format($c->outstanding_amount,2) }}</td>
              <td class="px-4 py-3">{{ $c->credit_date_bs }}</td>
              <td class="px-4 py-3">{{ $c->due_date_bs ?? '-' }}</td>
              <td class="px-4 py-3">
                <span class="px-2 py-1 rounded-full text-xs {{ $c->status==='open' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                  {{ ucfirst($c->status) }}
                </span>
              </td>
              <td class="px-4 py-3 text-right">
                <a href="{{ route('credits.show', $c) }}" class="rounded-lg bg-indigo-600 text-white px-3 py-1 text-xs font-semibold hover:bg-indigo-700">View</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="px-4 py-6 text-center text-gray-500">No credits found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $credits->links() }}</div>
  </div>
</section>

{{-- Auto format YYYY-MM-DD (insert "-" immediately after 4th and 6th digits) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('.date-mask');

  function formatWithTrailing(d) {
    // d = only digits, max 8 (YYYYMMDD)
    d = d.replace(/\D/g, '').slice(0, 8);
    const y = d.slice(0, 4);
    const m = d.slice(4, 6);
    const day = d.slice(6, 8);

    // Show trailing '-' as soon as year (4) or year+month (6) are complete
    if (d.length === 0) return '';
    if (d.length <= 4)  return d.length === 4 ? y + '-' : y;
    if (d.length <= 6)  return d.length === 6 ? y + '-' + m + '-' : y + '-' + m;
    return y + '-' + m + '-' + day;
  }

  inputs.forEach(el => {
    // Prevent non-digit typing
    el.addEventListener('keypress', e => {
      if (!/[0-9]/.test(e.key)) e.preventDefault();
    });

    // Handle input and paste
    el.addEventListener('input', e => {
      const before = el.value;
      el.value = formatWithTrailing(el.value);
      // Keep caret at end (simpler + works well for this mask)
      if (document.activeElement === el) {
        const len = el.value.length;
        el.setSelectionRange(len, len);
      }
    });

    // Normalize on blur as well
    el.addEventListener('blur', () => {
      el.value = formatWithTrailing(el.value);
    });
  });
});
</script>
@endsection
