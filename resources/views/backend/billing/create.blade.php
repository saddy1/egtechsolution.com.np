@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6 max-w-5xl mx-auto">
  <div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold">Create Bill</h2>
    <a href="{{ route('billing.index') }}" class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold">← Back</a>
  </div>

  @if ($errors->any())
    <div class="mb-4 rounded-xl bg-red-50 p-3 text-red-700">
      <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('billing.store') }}" method="POST" class="space-y-6">
    @csrf

    {{-- Bill header --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div>
        <label class="text-sm">Name *</label>
        <input type="text" name="billing_name" required class="w-full rounded-xl border px-3 py-2">
      </div>
      <div>
        <label class="text-sm">PAN (optional)</label>
        <input type="text" name="pan" class="w-full rounded-xl border px-3 py-2">
      </div>
      <div>
        <label class="text-sm">Address</label>
        <input type="text" name="address" class="w-full rounded-xl border px-3 py-2">
      </div>
        <div>
      <label class="text-sm">Bill Date (BS)</label>
      <input type="text" id="bill_date_bs" name="bill_date_bs" class="w-full rounded-xl border px-3 py-2" placeholder="YYYY-MM-DD" required>
      <p class="text-[11px] text-gray-500 mt-1">Leave empty to use today.</p>
    </div>
    </div>

   

    {{-- Items --}}
    <div class="rounded-2xl border p-4">
      <div class="font-semibold mb-3">Items</div>

      <div id="items" class="space-y-3">
        {{-- Row template (index 0) --}}
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 item-row" data-index="0">
          {{-- Product typeahead --}}
          <div class="md:col-span-3 relative">
            <label class="text-xs">Product *</label>
            <input type="text" class="product-input w-full rounded-xl border px-3 py-2"
                   placeholder="Type product name or SKU…" autocomplete="off">
            <input type="hidden" name="items[0][product_id]" class="product-id" required>
            <div class="hint text-xs text-gray-500 mt-1">
              <span class="stock-badge hidden">Stock: <b class="stock-val"></b></span>
              <span class="sku-badge hidden"> • SKU: <b class="sku-val"></b></span>
            </div>
            {{-- dropdown --}}
            <div class="dropdown absolute z-20 left-0 right-0 mt-1 bg-white border rounded-xl shadow-lg hidden max-h-64 overflow-auto"></div>
          </div>

          <div>
            <label class="text-xs">Qty *</label>
            <input type="number" name="items[0][quantity]" min="1"
                   class="qty-input w-full rounded-xl border px-3 py-2" required>
          </div>

          <div>
            <label class="text-xs">Selling Price *</label>
            <input type="number" step="0.01" min="0" name="items[0][selling_price]"
                   class="price-input w-full rounded-xl border px-3 py-2" required>
            <div class="text-[11px] text-gray-400 mt-1 default-price-hint hidden">
              Default: Rs. <span class="default-price-val"></span>
            </div>
          </div>

          <div class="md:col-span-2">
            <label class="text-xs">Remarks</label>
            <input type="text" name="items[0][item_remarks]" class="w-full rounded-xl border px-3 py-2">
          </div>

          {{-- per-row actions --}}
          <div class="md:col-span-6 flex justify-end gap-2">
            <button type="button" class="btn-remove-row hidden rounded-lg bg-red-50 text-red-700 px-3 py-1.5 text-xs font-semibold">
              Remove
            </button>
          </div>
        </div>
      </div>

      <button type="button" id="addItem"
              class="mt-3 rounded-lg bg-gray-100 px-3 py-2 text-sm font-semibold">
        + Add item
      </button>
    </div>

    {{-- Bill remarks --}}
    <div>
      <label class="text-sm">Bill Remarks</label>
      <textarea name="remarks" rows="2" class="w-full rounded-xl border px-3 py-2"></textarea>
    </div>

    {{-- Actions --}}
    <div class="flex justify-end gap-2">
      <button class="rounded-xl bg-indigo-600 px-4 py-2 text-white font-semibold">Save Bill</button>
    </div>
  </form>
</section>

{{-- Styles (inline for simplicity) --}}
<style>
  .dropdown .opt { padding:8px 10px; cursor:pointer; display:flex; justify-content:space-between; gap:8px; }
  .dropdown .opt:hover, .dropdown .opt.active { background:#f3f4f6; }
  .dropdown .name { font-weight:600; color:#111827; }
  .dropdown .meta { font-size:12px; color:#6b7280; }
</style>

{{-- Script --}}
<script>
  // Lightweight dataset for the typeahead
  const productsData = @json($productsForPicker);


  // Helpers
  const money = n => (Number(n)||0).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
  const bestPrice = p => p.sale_price>0 ? p.sale_price : p.price;

  // Render dropdown options list
  function renderOptions(listEl, items, activeIndex = -1) {
    listEl.innerHTML = '';
    items.slice(0, 12).forEach((p, idx) => {
      const div = document.createElement('div');
      div.className = 'opt' + (idx===activeIndex ? ' active' : '');
      div.dataset.id = p.id;
      div.innerHTML = `
        <div>
          <div class="name">${p.name}</div>
          <div class="meta">SKU: ${p.sku ?? '–'} • Stock: ${p.quantity}</div>
        </div>
        <div class="meta">Rs. ${money(bestPrice(p))}</div>
      `;
      listEl.appendChild(div);
    });
  }

  function attachTypeahead(row) {
    const input = row.querySelector('.product-input');
    const hiddenId = row.querySelector('.product-id');
    const dropdown = row.querySelector('.dropdown');
    const priceInput = row.querySelector('.price-input');
    const stockBadge = row.querySelector('.stock-badge');
    const stockVal = row.querySelector('.stock-val');
    const skuBadge = row.querySelector('.sku-badge');
    const skuVal = row.querySelector('.sku-val');
    const defaultHint = row.querySelector('.default-price-hint');
    const defaultVal = row.querySelector('.default-price-val');

    let results = [];
    let active = -1;
    let chosenId = null;

    const close = () => { dropdown.classList.add('hidden'); active=-1; };
    const open  = () => { dropdown.classList.remove('hidden'); };

    function chooseById(id) {
      const p = productsData.find(x=> String(x.id) === String(id));
      if (!p) return;
      chosenId = p.id;
      hiddenId.value = p.id;
      input.value = p.name;

      stockVal.textContent = p.quantity;
      stockBadge.classList.remove('hidden');
      skuVal.textContent = p.sku || '–';
      skuBadge.classList.remove('hidden');

      const def = bestPrice(p);
      if (!priceInput.value) priceInput.value = def;
      defaultVal.textContent = money(def);
      defaultHint.classList.remove('hidden');

      close();
    }

    input.addEventListener('input', e => {
      const q = e.target.value.trim().toLowerCase();
      hiddenId.value = '';
      chosenId = null;
      stockBadge.classList.add('hidden');
      skuBadge.classList.add('hidden');
      defaultHint.classList.add('hidden');

      if (!q) { close(); return; }
      results = productsData.filter(p =>
        (p.name && p.name.toLowerCase().includes(q)) ||
        (p.sku && p.sku.toLowerCase().includes(q))
      );
      if (results.length === 0) { close(); return; }
      renderOptions(dropdown, results, active=-1);
      open();
    });

    input.addEventListener('keydown', e => {
      if (dropdown.classList.contains('hidden')) return;
      const max = Math.min(results.length, 12)-1;
      if (e.key === 'ArrowDown') { e.preventDefault(); active = Math.min(max, active+1); renderOptions(dropdown, results, active); }
      if (e.key === 'ArrowUp')   { e.preventDefault(); active = Math.max(0, active-1);   renderOptions(dropdown, results, active); }
      if (e.key === 'Enter') {
        e.preventDefault();
        const target = (active>=0 && results[active]) ? results[active] : results[0];
        if (target) chooseById(target.id);
      }
      if (e.key === 'Escape') { close(); }
    });

    dropdown.addEventListener('click', e => {
      const opt = e.target.closest('.opt'); if (!opt) return;
      chooseById(opt.dataset.id);
    });

    document.addEventListener('click', (ev) => {
      if (!row.contains(ev.target)) close();
    }, {capture:true});

    input.addEventListener('blur', () => {
      if (!hiddenId.value) {
        input.value = '';
        priceInput.value = '';
      }
      setTimeout(close, 150);
    });
  }

  // Add/remove rows
  let idx = 1;
  const wrap = document.getElementById('items');

  function reindexRow(row, i) {
    row.dataset.index = i;
    row.querySelectorAll('input[name], select[name], textarea[name]').forEach(el => {
      el.name = el.name.replace(/\[\d+\]/, `[${i}]`);
    });
  }

  function addRow() {
    const tpl = document.querySelector('.item-row');
    const clone = tpl.cloneNode(true);

    // Reset inputs & badges
    clone.querySelectorAll('input').forEach(el => el.value = '');
    clone.querySelectorAll('.stock-badge,.sku-badge,.default-price-hint').forEach(el => el.classList.add('hidden'));
    clone.querySelector('.btn-remove-row').classList.remove('hidden');

    // update names with new index
    const newIndex = idx++;
    clone.querySelectorAll('input[name], select[name], textarea[name]').forEach(el => {
      el.name = el.name.replace('[0]', `[${newIndex}]`);
    });

    wrap.appendChild(clone);
    attachTypeahead(clone);

    // remove handler
    clone.querySelector('.btn-remove-row').addEventListener('click', () => {
      clone.remove();
      // (optional) reindex remaining rows
      document.querySelectorAll('.item-row').forEach((r, i) => reindexRow(r, i));
    });
  }

  // Init first row and add button
  attachTypeahead(document.querySelector('.item-row'));
  document.getElementById('addItem').addEventListener('click', addRow);

  // Validate before submit: ensure every row has product_id, qty, price
  document.querySelector('form').addEventListener('submit', (e) => {
    let ok = true;
    document.querySelectorAll('.item-row').forEach(row => {
      const pid = row.querySelector('.product-id').value;
      const qty = row.querySelector('.qty-input').value;
      const price = row.querySelector('.price-input').value;
      if (!pid || !qty || !price) ok = false;
    });
    if (!ok) {
      e.preventDefault();
      alert('Please select a product from suggestions and ensure Qty and Price are filled for every row.');
    }
  });
</script>

{{-- Nepali BS datepicker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nepali-datepicker-js@3.7.0/dist/nepali.datepicker.v3.7.min.css" />
<script src="https://cdn.jsdelivr.net/npm/nepali-datepicker-js@3.7.0/dist/nepali.datepicker.v3.7.min.js"></script>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    if (window.nepaliDatePicker) {
      const el = document.getElementById('bill_date_bs');
      if (el) nepaliDatePicker(el, { ndpYear:true, ndpMonth:true, ndpYearCount:30 });
    }
  });
</script>

@endsection
