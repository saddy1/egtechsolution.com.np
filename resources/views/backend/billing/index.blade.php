@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
      <h2 class="text-2xl font-bold">Bills</h2>
      <div class="flex gap-2">
        <a href="{{ route('billing.profit') }}" class="rounded-xl bg-amber-600 px-4 py-2 text-white text-sm font-semibold">Profit & Loss</a>
        <a href="{{ route('billing.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-white text-sm font-semibold">+ New Bill</a>
      </div>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-xl bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
      <div class="mb-4 rounded-xl bg-red-50 p-3 text-red-700">
        <ul class="list-disc pl-5 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-600">
          <tr>
            <th class="px-4 py-3 text-left">Bill No</th>
            <th class="px-4 py-3 text-left">Billing Name</th>
            <th class="px-4 py-3 text-left">PAN</th>
            <th class="px-4 py-3 text-left">Address</th>
            <th class="px-4 py-3 text-right">Subtotal</th>
            {{-- <th class="px-4 py-3 text-right">Cost</th> --}}
            {{-- <th class="px-4 py-3 text-right">Profit/Loss</th> --}}
            <th class="px-4 py-3 text-left">Date</th>
            <th class="px-4 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
          @foreach($bills as $b)
            <tr x-data="{ edit:false, snap:{} }"
                x-init="
                  snap = {
                    bill_no:'{{ addslashes($b->bill_no ?? '') }}',
                    billing_name:'{{ addslashes($b->billing_name) }}',
                    pan:'{{ addslashes($b->pan ?? '') }}',
                    address:'{{ addslashes($b->address ?? '') }}',
                    bill_date_bs:'{{ addslashes($b->bill_date_bs ?? '') }}',
                  }
                ">
              {{-- BILL NO --}}
              <td class="px-4 py-3 font-semibold">
                <div x-show="!edit">{{ $b->bill_no ?? '—' }}</div>
                <input x-show="edit" type="text" name="bill_no" form="f-{{ $b->id }}"
                       class="w-full rounded-lg border px-2 py-1 text-sm"
                       value="{{ old('bill_no', $b->bill_no) }}">
              </td>

              {{-- NAME --}}
              <td class="px-4 py-3">
                <div x-show="!edit">{{ $b->billing_name }}</div>
                <input x-show="edit" type="text" name="billing_name" form="f-{{ $b->id }}"
                       class="w-full rounded-lg border px-2 py-1 text-sm"
                       value="{{ old('billing_name', $b->billing_name) }}">
              </td>

              {{-- PAN --}}
              <td class="px-4 py-3">
                <div x-show="!edit">{{ $b->pan ?? '-' }}</div>
                <input x-show="edit" type="text" name="pan" form="f-{{ $b->id }}"
                       class="w-full rounded-lg border px-2 py-1 text-sm"
                       value="{{ old('pan', $b->pan) }}">
              </td>

              {{-- ADDRESS --}}
              <td class="px-4 py-3">
                <div x-show="!edit">{{ $b->address ?? '-' }}</div>
                <input x-show="edit" type="text" name="address" form="f-{{ $b->id }}"
                       class="w-full rounded-lg border px-2 py-1 text-sm"
                       value="{{ old('address', $b->address) }}">
              </td>

              {{-- SUBTOTAL (RO) --}}
              <td class="px-4 py-3 text-right">Rs. {{ number_format($b->subtotal,2) }}</td>

              {{-- COST (RO) --}}
              {{-- <td class="px-4 py-3 text-right">Rs. {{ number_format($b->total_cost,2) }}</td> --}}

              {{-- PROFIT/LOSS (RO) --}}
              {{-- <td class="px-4 py-3 text-right {{ $b->profit >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                {{ $b->profit < 0 ? '−' : '' }}Rs. {{ number_format(abs($b->profit),2) }}
              </td> --}}

              {{-- DATE (BS preferred) --}}
              <td class="px-4 py-3">
                <div x-show="!edit">
                  @if(!empty($b->bill_date_bs))
                    {{ $b->bill_date_bs }} <span class="text-gray-400">(BS)</span>
                  @else
                    {{ $b->created_at->format('Y-m-d') }}
                  @endif
                </div>
                <input x-show="edit" type="text" name="bill_date_bs" form="f-{{ $b->id }}"
                       class="w-full rounded-lg border px-2 py-1 text-sm ndp"
                       placeholder="YYYY-MM-DD"
                       value="{{ old('bill_date_bs', $b->bill_date_bs) }}">
              </td>

              {{-- ACTIONS --}}
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                  {{-- EDIT --}}
                  <button type="button" x-show="!edit" @click="
                      edit=true;
                      $nextTick(() => {
                        const el = $root.querySelector('.ndp');
                        if (el && window.nepaliDatePicker && !el.dataset.ndpInited) {
                          nepaliDatePicker(el, { ndpYear:true, ndpMonth:true, ndpYearCount:30 });
                          el.dataset.ndpInited = '1';
                        }
                      });
                    " title="Edit inline" class="p-2 rounded-lg hover:bg-gray-100">✏️</button>

                  {{-- SAVE --}}
                  <button x-show="edit" type="submit" form="f-{{ $b->id }}"
                          class="p-2 rounded-lg hover:bg-gray-100 text-emerald-700" title="Save">💾</button>

                  {{-- CANCEL --}}
                  <button x-show="edit" type="button" @click="
                      const f = document.getElementById('f-{{ $b->id }}');
                      f.querySelector('[name=bill_no]').value      = snap.bill_no;
                      f.querySelector('[name=billing_name]').value = snap.billing_name;
                      f.querySelector('[name=pan]').value          = snap.pan;
                      f.querySelector('[name=address]').value      = snap.address;
                      f.querySelector('[name=bill_date_bs]').value = snap.bill_date_bs;
                      edit=false;
                    " class="p-2 rounded-lg hover:bg-gray-100 text-gray-600" title="Cancel">✖</button>

                  {{-- PRINT --}}
                  <a href="{{ route('billing.print', $b) }}" title="Print" target="_blank"
                     class="p-2 rounded-lg hover:bg-gray-100">🖨️</a>

                  {{-- DELETE --}}
                  <form action="{{ route('billing.destroy', $b) }}" method="POST"
                        onsubmit="return confirm('Delete this bill? Stock will be restored.');">
                    @csrf @method('DELETE')
                    <button type="submit" title="Delete" class="p-2 rounded-lg hover:bg-gray-100">🗑️</button>
                  </form>
                </div>

                {{-- tiny hidden form per row --}}
                <form id="f-{{ $b->id }}" method="POST" action="{{ route('billing.inline.update', $b) }}" class="hidden">
                  @csrf @method('PUT')
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $bills->links() }}</div>
  </div>
</section>
@endsection
