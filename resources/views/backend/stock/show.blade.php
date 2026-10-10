@extends('backend.layout.app')

@section('content')
    <section class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-5xl mx-auto">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                </div>
                <a href="{{ route('stock.stock.index') }}"
                    class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold hover:bg-gray-200">← Back</a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-xl bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 p-3 text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Product quick stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="rounded-xl border p-4">
                    <div class="text-xs text-gray-500">Quantity</div>
                    <div class="text-2xl font-bold">{{ $product->quantity }}</div>
                </div>
                <div class="rounded-xl border p-4">
                    <div class="text-xs text-gray-500">Cost Price</div>
                    <div class="text-xl font-semibold">Rs. {{ number_format($product->cost_price, 2) }}</div>
                </div>
                <div class="rounded-xl border p-4">
                    <div class="text-xs text-gray-500">Regular Price</div>
                    <div class="text-xl font-semibold">Rs. {{ number_format($product->price, 2) }}</div>
                </div>
                <div class="rounded-xl border p-4">
                    <div class="text-xs text-gray-500">Selling Price</div>
                    <div class="text-xl font-semibold">Rs. {{ number_format($product->sale_price, 2) }}</div>
                </div>
            </div>

            {{-- Refill card --}}
            <div class="rounded-2xl border bg-white shadow-sm p-5 mb-8" x-data="{ showPrice: false, showPurchase: false }">
                <h3 class="text-lg font-semibold mb-4">Refill Stock</h3>

                <form action="{{ route('stock.refill', $product) }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    @csrf

                    {{-- Add Quantity --}}
                    <div class="md:col-span-3">
                        <label class="text-sm text-gray-700">Add Qty *</label>
                        <input type="number" name="add_quantity" min="1" required
                            class="w-full rounded-xl border px-3 py-2">
                    </div>

                    {{-- Toggle price edit --}}
                    <div class="md:col-span-3 flex items-end">
                        <button type="button" @click="showPrice = !showPrice"
                            class="w-full rounded-xl bg-gray-100 px-3 py-2 text-sm font-semibold hover:bg-gray-200">
                            <span x-show="!showPrice">Edit prices (opt)</span>
                            <span x-show="showPrice">Hide prices</span>
                        </button>
                    </div>

                    {{-- Toggle purchase details --}}
                    <div class="md:col-span-3 flex items-end">
                        <button type="button" @click="showPurchase = !showPurchase"
                            class="w-full rounded-xl bg-gray-100 px-3 py-2 text-sm font-semibold hover:bg-gray-200">
                            <span x-show="!showPurchase">Purchase details (opt)</span>
                            <span x-show="showPurchase">Hide purchase</span>
                        </button>
                    </div>

                    <div class="md:col-span-3 flex items-end">
                        <button
                            class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-white font-semibold hover:bg-emerald-700">Refill</button>
                    </div>

                    {{-- Optional price edits --}}
                    <div class="md:col-span-12 grid grid-cols-1 md:grid-cols-3 gap-4" x-show="showPrice" x-cloak>
                        <div>
                            <label class="text-sm text-gray-700">Cost Price (opt)</label>
                            <input type="number" name="cost_price" step="0.01" min="0"
                                class="w-full rounded-xl border px-3 py-2"
                                placeholder="{{ number_format($product->cost_price, 2) }}">
                        </div>
                        <div>
                            <label class="text-sm text-gray-700">Regular Price (opt)</label>
                            <input type="number" name="price" step="0.01" min="0"
                                class="w-full rounded-xl border px-3 py-2"
                                placeholder="{{ number_format($product->price, 2) }}">
                        </div>
                        <div>
                            <label class="text-sm text-gray-700">Selling Price (opt)</label>
                            <input type="number" name="sale_price" step="0.01" min="0"
                                class="w-full rounded-xl border px-3 py-2"
                                placeholder="{{ number_format($product->sale_price, 2) }}">
                        </div>
                    </div>

                    {{-- Optional purchase logging --}}
                    <div class="md:col-span-12 grid grid-cols-1 md:grid-cols-4 gap-4" x-show="showPurchase" x-cloak>
                        <div>
                            <label class="text-sm text-gray-700">Supplier</label>
                            <select name="supplier_id" class="w-full rounded-xl border px-3 py-2">
                                <option value="">— choose —</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div>
                            <label class="text-sm text-gray-700">Rate (excl. VAT)</label>
                            <input type="number" name="rate_excl_vat" step="0.01" min="0"
                                class="w-full rounded-xl border px-3 py-2" placeholder="e.g., 1000.00">
                            <p class="text-xs text-gray-500 mt-1">13% VAT auto-applied</p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-700">Bill No</label>
                            <input type="text" name="bill_no" class="w-full rounded-xl border px-3 py-2"
                                placeholder="INV-1234">
                        </div>

                        <div">
                            <label class="text-xs text-gray-600">Date (BS)</label>
                            <input type="text" name="purchased_at"
                                value="{{ old('purchased_at', $row->purchased_at_bs ?? '') }}"
                                class="w-full rounded-xl border px-3 py-2 date-mask" placeholder="YYYY-MM-DD"
                                inputmode="numeric" maxlength="10" pattern="\d{4}-\d{2}-\d{2}" autocomplete="off"
                                title="Use format YYYY-MM-DD">
                    </div>
            </div>
            </form>
        </div>

        {{-- Purchase History --}}
        <div class="rounded-2xl border bg-white shadow-sm">
            <div class="px-5 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold">Purchase History</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-left">Supplier</th>
                            <th class="px-4 py-3 text-right">Qty</th>
                            <th class="px-4 py-3 text-right">Rate excl. VAT (RS)</th>
                            <th class="px-4 py-3 text-right">13% VAT (RS)</th>
                            <th class="px-4 py-3 text-right">Rate incl. VAT (RS)</th>
                            <th class="px-4 py-3 text-right">Total (RS)</th>
                            <th class="px-4 py-3 text-left">Bill No</th>
                            <th class="px-4 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($purchases as $row)
                            {{-- DISPLAY ROW --}}
                            <tr id="row-show-{{ $row->id }}">
                               <td class="px-4 py-3">
  {{ optional($row->purchased_at)->format('Y-m-d') ?? '-' }}
</td>

                                <td class="px-4 py-3">
                                    {{ $row->supplier_name ?? (optional($row->supplier)->name ?? '-') }}</td>
                                <td class="px-4 py-3 text-right">{{ $row->quantity }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($row->rate_excl_vat, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    {{ number_format($row->rate_incl_vat - $row->rate_excl_vat, 2) }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($row->rate_incl_vat, 2) }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($row->total, 2) }}</td>
                                <td class="px-4 py-3">{{ $row->bill_no ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- EDIT ICON --}}
                                        <button type="button" title="Edit" class="p-2 rounded-lg hover:bg-gray-100"
                                            onclick="toggleEdit({{ $row->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.714 0l-1.157 1.157 3.714 3.714 1.157-1.157a2.625 2.625 0 0 0 0-3.714z" />
                                                <path d="M3 17.25V21h3.75L19.44 8.31l-3.714-3.714L3 17.25z" />
                                            </svg>
                                        </button>

                                        {{-- DELETE ICON --}}
                                        <form action="{{ route('stock.purchase.delete', $row) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Delete this purchase record?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Delete"
                                                class="p-2 rounded-lg hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600"
                                                    viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M9 3h6a1 1 0 0 1 1 1v1h4v2H4V5h4V4a1 1 0 0 1 1-1z" />
                                                    <path d="M7 9h2v10H7zM11 9h2v10h-2zM15 9h2v10h-2z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- INLINE EDIT ROW (hidden by default) --}}
                            <tr id="row-edit-{{ $row->id }}" class="hidden bg-gray-50">
                                <td colspan="9" class="px-4 py-4">
                                    <form method="POST" action="{{ route('stock.purchase.update', $row) }}"
                                        class="grid grid-cols-12 gap-3 items-end">
                                        @csrf @method('PUT')

                                        <div class="col-span-3">
                                            <label class="text-xs text-gray-600">Supplier</label>
                                            <select name="supplier_id" class="w-full rounded-xl border px-3 py-2">
                                                <option value="">— choose —</option>
                                                @foreach ($suppliers as $s)
                                                    <option value="{{ $s->id }}" @selected($row->supplier_id == $s->id)>
                                                        {{ $s->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="col-span-2">
                                            <label class="text-xs text-gray-600">Quantity *</label>
                                            <input type="number" name="quantity" min="1"
                                                value="{{ old('quantity', $row->quantity) }}"
                                                class="w-full rounded-xl border px-3 py-2" required>
                                        </div>

                                        <div class="col-span-2">
                                            <label class="text-xs text-gray-600">Rate (excl. VAT) *</label>
                                            <input type="number" step="0.01" min="0" name="rate_excl_vat"
                                                value="{{ old('rate_excl_vat', $row->rate_excl_vat) }}"
                                                class="w-full rounded-xl border px-3 py-2" required>

                                        </div>

                                        <div class="col-span-2">
                                            <label class="text-xs text-gray-600">Bill No</label>
                                            <input type="text" name="bill_no"
                                                value="{{ old('bill_no', $row->bill_no) }}"
                                                class="w-full rounded-xl border px-3 py-2">
                                        </div>

                                        <div class="col-span-2">
                                            <label class="text-xs text-gray-600">Date (BS)</label>
                                            <input type="text" name="purchased_at"
                                                value="{{ old('purchased_at', $row->purchased_at_bs ?? '') }}"
                                                class="w-full rounded-xl border px-3 py-2 date-mask"
                                                placeholder="YYYY-MM-DD" inputmode="numeric" maxlength="10"
                                                pattern="\d{4}-\d{2}-\d{2}" autocomplete="off"
                                                title="Use format YYYY-MM-DD">
                                        </div>


                                        <div class="col-span-1">
                                            <button
                                                class="w-full rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white">Save</button>
                                        </div>
                                        <div class="col-span-1">
                                            <button type="button" onclick="toggleEdit({{ $row->id }})"
                                                class="w-full rounded-xl bg-gray-100 px-3 py-2 text-xs font-semibold">Cancel</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-gray-500">No purchases yet.</td>
                            </tr>
                        @endforelse
                    </tbody>



                </table>
            </div>

            <div class="px-5 py-3">{{ $purchases->links() }}</div>
        </div>
        </div>
    </section>

    {{-- Alpine (if not already loaded globally) --}}
    

    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
    <script>
        function toggleEdit(id) {
            const show = document.getElementById('row-show-' + id);
            const edit = document.getElementById('row-edit-' + id);
            if (!show || !edit) return;
            show.classList.toggle('hidden');
            edit.classList.toggle('hidden');
        }
    </script>
    <script>
        (function() {
            function toMasked(value) {
                let d = (value || '').replace(/\D/g, '').slice(0, 8);
                const y = d.slice(0, 4),
                    m = d.slice(4, 6),
                    day = d.slice(6, 8);

                if (d.length === 0) return '';
                if (d.length < 4) return d;
                if (d.length === 4) return y + '-';
                if (d.length < 6) return y + '-' + m;
                if (d.length === 6) return y + '-' + m + '-';
                return y + '-' + m + '-' + day;
            }

            function attachMask(el) {
                el.addEventListener('keypress', e => {
                    if (!/[0-9]/.test(e.key)) e.preventDefault();
                });
                el.addEventListener('input', () => {
                    const masked = toMasked(el.value);
                    if (el.value !== masked) el.value = masked;
                    const len = el.value.length;
                    el.setSelectionRange(len, len);
                });
                el.addEventListener('blur', () => {
                    el.value = toMasked(el.value);
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.date-mask').forEach(attachMask);
            });
        })();
    </script>


@endsection
