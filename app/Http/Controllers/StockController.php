<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierPurchase;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\SaleItem;

class StockController extends Controller
{
    // Page 1: Index (low stock first)
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

    $products = Product::query()
        ->with('supplier') // so supplier name is available
        ->when($q, function ($qb) use ($q) {
            $like = '%'.$q.'%';
            $qb->where(function ($qq) use ($like) {
                $qq->where('name', 'like', $like)
                   ->orWhere('sku', 'like', $like)
                   ->orWhere('barcode', 'like', $like);
            })
            ->orWhereHas('supplier', function ($s) use ($like) {
                $s->where('name', 'like', $like);
            });
        })
        // keep low stock first, then by qty asc
        ->orderByRaw('CASE WHEN quantity <= 5 THEN 0 ELSE 1 END ASC')
        ->orderBy('quantity', 'asc')
        ->orderBy('updated_at', 'desc')
        ->paginate(20)
        ->appends($request->only('q')); // keep q on pagination links

    return view('backend.stock.index', compact('products', 'q'));
    }

    // Page 2: Show product + refill + purchase history
    public function show(Product $product)
    {
        $suppliers = Supplier::orderBy('name')->get(['id','name']);
        $purchases = SupplierPurchase::where('product_id', $product->id)
            ->orderBy('purchased_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('backend.stock.show', compact('product','suppliers','purchases'));
    }

    // Refill (adds quantity, optional price edits; logs purchase if rate_excl_vat provided)
    public function refill(Request $request, Product $product)
    {
        $data = $request->validate([
            'add_quantity'  => ['required','integer','min:1'],

            // optional direct edits on product
            'cost_price'    => ['nullable','numeric','min:0'],
            'price'         => ['nullable','numeric','min:0'],
            'sale_price'    => ['nullable','numeric','min:0'],

            // purchase log (optional; if rate_excl_vat is filled, we log it)
            'supplier_id'   => ['nullable','exists:suppliers,id'],
            'supplier_name' => ['nullable','string','max:120'],
            'rate_excl_vat' => ['nullable','numeric','min:0'],
            'bill_no'       => ['nullable','string','max:100'],
            'purchased_at'  => ['nullable','date'],
        ]);

        // 1) Always add to stock
        $product->quantity = (int)$product->quantity + (int)$data['add_quantity'];

        // 2) Optional product price edits
        foreach (['cost_price','price','sale_price'] as $f) {
            if ($request->filled($f)) {
                $product->{$f} = $data[$f];
            }
        }
        $product->save();

        // 3) Optionally log a supplier purchase if a rate is provided
        if ($request->filled('rate_excl_vat')) {
            $rateEx = (float)$data['rate_excl_vat'];
            $rateIn = round($rateEx * 1.13, 2); // add 13% VAT
            $qty    = (int)$data['add_quantity'];
            $total  = round($qty * $rateIn, 2);

            $supplierName = null;
            if (!empty($data['supplier_id'])) {
                $supplierName = optional(Supplier::find($data['supplier_id']))->name;
            }
            if (!$supplierName && !empty($data['supplier_name'])) {
                $supplierName = $data['supplier_name'];
            }

            SupplierPurchase::create([
                'supplier_id'   => $data['supplier_id'] ?? null,
                'supplier_name' => $supplierName,
                'product_id'    => $product->id,
                'product_name'  => $product->name,
                'quantity'      => $qty,
                'rate_excl_vat' => $rateEx,
                'rate_incl_vat' => $rateIn,
                'total'         => $total,
                'bill_no'       => $data['bill_no'] ?? null,
                'purchased_at'  => $data['purchased_at'] ?? now()->toDateString(),
            ]);
        }

        return redirect()
            ->route('stock.show', $product)
            ->with('success', 'Stock refilled successfully.');
    }


public function updatePurchase(Request $request, SupplierPurchase $purchase)
{
    $data = $request->validate([
        'supplier_id'   => ['nullable','exists:suppliers,id'],
        'supplier_name' => ['nullable','string','max:120'],
        'quantity'      => ['required','integer','min:1'],
        'rate_excl_vat' => ['required','numeric','min:0'],
        'bill_no'       => ['nullable','string','max:100'],
        'purchased_at'  => ['nullable','date'],
    ]);

    $oldQty = (int)$purchase->quantity;
    $newQty = (int)$data['quantity'];
    $delta  = $newQty - $oldQty; // + means add to stock, - means reduce stock

    $rateEx = (float)$data['rate_excl_vat'];
    $rateIn = round($rateEx * 1.13, 2);
    $total  = round($newQty * $rateIn, 2);

    // Resolve snapshot supplier name
    $supplierName = $data['supplier_name'] ?? null;
    if (!empty($data['supplier_id'])) {
        $supplierName = Supplier::find($data['supplier_id'])->name ?? $supplierName;
    }

    DB::transaction(function () use ($purchase, $data, $newQty, $rateEx, $rateIn, $total, $supplierName, $delta) {
        // 1) Update purchase row
        $purchase->update([
            'supplier_id'   => $data['supplier_id'] ?? null,
            'supplier_name' => $supplierName,
            'quantity'      => $newQty,
            'rate_excl_vat' => $rateEx,
            'rate_incl_vat' => $rateIn,
            'total'         => $total,
            'bill_no'       => $data['bill_no'] ?? null,
            'purchased_at'  => $data['purchased_at'] ?? $purchase->purchased_at,
        ]);

        // 2) Adjust product stock by delta
        $product = Product::find($purchase->product_id);
        if ($product) {
            $product->quantity = (int)$product->quantity + $delta;
            if ($product->quantity < 0) { $product->quantity = 0; } // safety
            $product->save();
        }
    });

    return back()->with('success', 'Purchase updated and stock adjusted.');
}




public function summary(Request $request)
{
    // --- Filters: BS dates as strings (YYYY-MM-DD) ---
    $from_bs = $request->get('from_bs');
    $to_bs   = $request->get('to_bs');

    // accept only strict YYYY-MM-DD; otherwise treat as null
    $from_bs = (is_string($from_bs) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_bs)) ? $from_bs : null;
    $to_bs   = (is_string($to_bs)   && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_bs))   ? $to_bs   : null;

    // If your selling prices are VAT-INCLUSIVE, set true. If EXCLUSIVE, set false.
    // Adjust VAT rate if needed.
    $VAT_RATE = 0.13;
    $SALES_PRICES_INCLUDE_VAT = true;

    // --- Purchases (from supplier_purchases) filtered by purchased_at_bs (BS string) ---
    // Assumes you have a column `purchased_at_bs` stored as 'YYYY-MM-DD' (BS).
    $purchaseRows = SupplierPurchase::query()
        ->when($from_bs, fn($q) => $q->where('purchased_at', '>=', $from_bs))
        ->when($to_bs,   fn($q) => $q->where('purchased_at', '<=', $to_bs))
        ->get();

    $purchase_excl = $purchaseRows->sum(fn($r) => $r->quantity * (float)$r->rate_excl_vat);
    $purchase_incl = $purchaseRows->sum(fn($r) => (float)$r->total); // already qty * rate_incl_vat
    $purchase_vat  = $purchase_incl - $purchase_excl;

    // --- Sales (from sale_items) filtered by parent sale bill_date_bs (BS string) ---
    // Assumes you have Sales.bill_date_bs stored as 'YYYY-MM-DD' (BS).
    $saleItems = SaleItem::with('sale')
        ->when($from_bs, fn($q) => $q->whereHas('sale', fn($s) => $s->where('bill_date_bs', '>=', $from_bs)))
        ->when($to_bs,   fn($q) => $q->whereHas('sale', fn($s) => $s->where('bill_date_bs', '<=', $to_bs)))
        ->get();

    $sales_gross = $saleItems->sum('line_total'); // what you charged (sum of qty * selling_price)

    if ($SALES_PRICES_INCLUDE_VAT) {
        // Prices already include VAT → derive excl and VAT parts
        $sales_excl = $sales_gross / (1 + $VAT_RATE);
        $sales_vat  = $sales_gross - $sales_excl;
        $sales_incl = $sales_gross;
    } else {
        // Prices are before VAT → add VAT on top
        $sales_excl = $sales_gross;
        $sales_vat  = $sales_excl * $VAT_RATE;
        $sales_incl = $sales_excl + $sales_vat;
    }

    // --- Net (Sales − Purchases) per column ---
    $net_excl = $sales_excl - $purchase_excl;
    $net_vat  = $sales_vat  - $purchase_vat;
    $net_incl = $sales_incl - $purchase_incl;

    // Round nicely for display
    $totals = [
        'purchase_excl' => round($purchase_excl, 2),
        'purchase_vat'  => round($purchase_vat,  2),
        'purchase_incl' => round($purchase_incl, 2),

        'sales_excl'    => round($sales_excl,    2),
        'sales_vat'     => round($sales_vat,     2),
        'sales_incl'    => round($sales_incl,    2),

        'net_excl'      => round($net_excl,      2),
        'net_vat'       => round($net_vat,       2),
        'net_incl'      => round($net_incl,      2),
    ];

    return view('backend.stock.summary', [
        'from_bs' => $from_bs,
        'to_bs'   => $to_bs,
        'totals'  => $totals,
        'vatRatePercent'    => (int)round($VAT_RATE * 100),
        'pricesIncludeVat'  => $SALES_PRICES_INCLUDE_VAT,
    ]);
}


public function bySupplier(Supplier $supplier, Request $request)
{
    $q       = trim((string) $request->get('q', ''));
    $from_bs = $request->get('from_bs');
    $to_bs   = $request->get('to_bs');

    // normalize BS inputs to strict YYYY-MM-DD
    $from_bs = (is_string($from_bs) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_bs)) ? $from_bs : null;
    $to_bs   = (is_string($to_bs)   && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_bs))   ? $to_bs   : null;

    // Base query: match by supplier_id OR fallback to supplier_name (for historical rows)
    $base = SupplierPurchase::query()
        ->where(function($w) use ($supplier) {
            $w->where('supplier_id', $supplier->id)
              ->orWhere(function($x) use ($supplier) {
                  $x->whereNull('supplier_id')
                    ->where('supplier_name', $supplier->name);
              });
        });

    // Filters
    $base->when($q, function($qb) use ($q) {
            $like = '%'.$q.'%';
            $qb->where(function($qq) use ($like) {
                $qq->where('product_name', 'like', $like)
                   ->orWhere('bill_no', 'like', $like);
            });
        })
        // Filter by BS string dates (YYYY-MM-DD) on purchased_at_bs
        ->when($from_bs, fn($qb) => $qb->where('purchased_at', '>=', $from_bs))
        ->when($to_bs,   fn($qb) => $qb->where('purchased_at', '<=', $to_bs));

    // Clone for totals (no pagination)
    $totalsQuery = (clone $base)->selectRaw('
        COALESCE(SUM(quantity * rate_excl_vat),0) as sum_excl,
        COALESCE(SUM(quantity * (rate_incl_vat - rate_excl_vat)),0) as sum_vat,
        COALESCE(SUM(total),0) as sum_total,
        COALESCE(SUM(quantity),0) as sum_qty
    ')->first();

    // Order primarily by BS date; fallback by created_at
    $purchases = $base->orderByDesc('purchased_at')
                      ->orderByDesc('created_at')
                      ->paginate(20)
                      ->appends($request->only('q','from_bs','to_bs'));

    return view('backend.supplier.purchases', [
        'supplier'  => $supplier,
        'purchases' => $purchases,
        'q'         => $q,
        'from_bs'   => $from_bs,
        'to_bs'     => $to_bs,
        'totals'    => [
            'sum_excl'  => (float)$totalsQuery->sum_excl,
            'sum_vat'   => (float)$totalsQuery->sum_vat,
            'sum_total' => (float)$totalsQuery->sum_total,
            'sum_qty'   => (int)$totalsQuery->sum_qty,
        ],
    ]);
}





    // Delete a purchase record (but do NOT adjust stock)
    public function deletePurchase(SupplierPurchase $purchase)
    {
        $purchase->delete();
        return redirect()->back()->with('success', 'Purchase record deleted successfully.');    
    }
}
