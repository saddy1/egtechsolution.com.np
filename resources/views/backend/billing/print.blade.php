{{-- resources/views/backend/billing/print.blade.php --}}
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice {{ $sale->bill_no }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  /* ---------- Base & Colors ---------- */
  :root {
    --ink:#0f172a;        /* slate-900 */
    --muted:#64748b;      /* slate-500 */
    --line:#e2e8f0;       /* slate-200 */
    --brand:#111827;      /* gray-900 */
    --bg:#ffffff;
    --pill:#f1f5f9;       /* slate-100 */
  }
  /* Dark thin grid table */
.table-thin-dark {
  width: 100%;
  border-collapse: collapse;
  border: 1px solid #0f172a;      /* outer border (slate-900) */
}

.table-thin-dark th,
.table-thin-dark td {
  border: 1px solid #0f172a;      /* thin dark cell borders */
  padding: 8px 10px;
  font-size: 14px;
}

.table-thin-dark thead th {
  background: #111827;            /* dark header */
  color: #ffffff;                  /* white text */
  text-transform: uppercase;
  letter-spacing: .04em;
  font-size: 12px;
}

.table-thin-dark .right { text-align: right; }
.table-thin-dark .amount { white-space: nowrap; }

/* Print tweaks: keep borders dark and hairline-ish */
@media print {
  .table-thin-dark th,
  .table-thin-dark td {
    border-color: #000;            /* ensure strong on printers */
  }
}

  * { box-sizing:border-box; }
  html, body { background:var(--bg); color:var(--ink); font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial; }
  body { margin:0; padding:0; }
  /* The page wrapper adds side margins visually; print margins are set in @page */
  .page {
    max-width: 860px;               /* keep line length pleasant */
    margin: 0 auto;
    padding: 30px 50px;             /* visual margin on screens */
  }

  /* ---------- Print Margins ---------- */
  @media print {
    @page { size: A4; margin: 14mm 20mm; } /* top/bottom 14mm, left/right 16mm */
    body { padding:0; }
    .page { padding:30px 50px; }             /* print uses @page margins; remove inner padding */
    .no-print { display:none !important; }
  }

  /* ---------- Header ---------- */
  .header {
    display:flex; gap:24px; align-items:flex-start; justify-content:space-between; margin-bottom:18px;
  }
  .brand h1 { margin:0; font-size:20px; letter-spacing:.3px; color:var(--brand); }
  .muted { color:var(--muted); font-size:12px; }
  .badge {
    display:inline-block; background:var(--pill); color:var(--ink);
    font-size:12px; padding:4px 8px; border-radius:999px; border:1px solid var(--line);
  }
  .title { text-align:right; }
  .title h2 { margin:0; font-size:26px; letter-spacing:.2px; }

  /* ---------- Blocks ---------- */
  .row { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:14px; }
  .card { border:1px solid var(--line); border-radius:12px; padding:12px 14px; }
  .card strong { display:block; margin-bottom:4px; }

  /* ---------- Table ---------- */
  table { width:100%; border-collapse:collapse; margin-top:8px; }
  thead th {
    text-align:left; font-size:12px; color:var(--muted);
    text-transform:uppercase; letter-spacing:.04em;
    border-bottom:1px solid var(--line); padding:8px 10px;
  }
  tbody td { padding:10px 10px; border-bottom:1px solid var(--line); font-size:14px; vertical-align:top; }
  .right { text-align:right; }
  .amount { white-space:nowrap; }

  /* ---------- Totals Box ---------- */
  .totals {
    display:grid; gap:6px; width:320px; margin-left:auto; margin-top:14px;
    border:1px solid var(--line); border-radius:12px; padding:10px 12px; background:#fcfcfd;
  }
  .totals .row { display:flex; justify-content:space-between; align-items:center; margin:0; }
  .totals .row div:first-child { color:var(--muted); font-size:13px; }
  .totals .total { font-weight:700; font-size:15px; }
  .rule { height:1px; background:var(--line); margin:6px 0; }

  /* ---------- Footer ---------- */
  .footer {
    display:flex; justify-content:space-between; gap:24px; margin-top:26px;
    color:var(--muted); font-size:12px;
  }
  .sig {
    margin-top:42px; text-align:right;
  }
  .sig .line {
    border-top:1px solid var(--line); width:220px; margin-left:auto; padding-top:6px;
  }

  /* ---------- Buttons (screen only) ---------- */
  .actions { display:flex; gap:8px; margin:16px 0 0; }
  .btn {
    border:1px solid var(--line); background:#f8fafc; color:#0f172a; border-radius:10px;
    padding:8px 12px; cursor:pointer; font-size:14px;
  }
  .btn:hover { filter:brightness(.98); }
</style>
</head>
<body>
<div class="page">
  {{-- Top Bar (screen only) --}}
  <div class="actions no-print">
    <button class="btn" onclick="window.print()">Print</button>
    <button class="btn" onclick="window.close()">Close</button>
  </div>

  {{-- Header --}}
  <header class="header">
    <div class="brand">
      <h1>EG Tech Solution</h1>
      <div class="muted">Sahid Gate, Chatakpur, Dhangadhi City</div>
      <div class="muted">Phone: +977-9848583865</div>
      <div class="muted">PAN: 123456789</div>
    </div>
    <div class="title">
      <h2>Tax Invoice</h2>
      <div class="badge"># {{ $sale->bill_no }}</div>
      <div class="muted">{{ $sale->created_at->format('d M, Y') }}</div>
      @if($sale->bill_date_bs)
        <div class="muted">({{ $sale->bill_date_bs }} BS)</div>
      @endif
    </div>
  </header>

  {{-- Parties --}}
  <div class="row">
    <div class="card">
      <strong>Bill To</strong>
      <div>{{ $sale->billing_name }}</div>
      @if($sale->address)<div class="muted">{{ $sale->address }}</div>@endif
      @if($sale->pan)<div class="muted">PAN: {{ $sale->pan }}</div>@endif
    </div>
    <div class="card">
      <strong>Remarks</strong>
      <div class="muted">{{ $sale->remarks ?? '—' }}</div>
    </div>
  </div>

  {{-- Items --}}
<table class="table-thin-dark">
  <thead>
    <tr>
      <th>S.N</th>
      <th>Item</th>
      <th class="right">Qty</th>
      <th class="right">Rate (Rs.)</th>
      <th class="right">Amount (Rs.)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($sale->items as $it)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
          <div><strong>{{ $it->product_name }}</strong></div>
          @if($it->item_remarks)<div class="muted">{{ $it->item_remarks }}</div>@endif
        </td>
        <td class="right">{{ $it->quantity }}</td>
        <td class="right amount">{{ number_format($it->selling_price,2) }}</td>
        <td class="right amount">{{ number_format($it->line_total,2) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>


  {{-- Totals --}}
  @php
    // If you later have VAT/discount, compute and show here
    $subtotal = round((float)$sale->subtotal, 2);
    $vatRate  = 0; // e.g., 13 for 13%
    $vatAmt   = round($subtotal * ($vatRate/100), 2);
    $grand    = round($subtotal + $vatAmt, 2);
  @endphp

  <div class="totals">
    <div class="row"><div>Subtotal</div><div class="amount">Rs. {{ number_format($subtotal,2) }}</div></div>
    @if($vatRate > 0)
      <div class="row"><div>VAT ({{ $vatRate }}%)</div><div class="amount">Rs. {{ number_format($vatAmt,2) }}</div></div>
    @endif
    <div class="rule"></div>
    <div class="row total"><div>Total</div><div class="amount">Rs. {{ number_format($grand,2) }}</div></div>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <div>Generated on {{ now()->format('d M, Y H:i') }}</div>
    <div class="sig">
      <div class="line">Authorized Signature</div>
    </div>
  </div>
</div>
</body>
</html>
