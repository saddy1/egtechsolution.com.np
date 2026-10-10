<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    /** Auto bill no like: INV-0001 */
    protected function generateBillNo(): string
    {
        $prefix = 'INV-';
        $last = Sale::where('bill_no', 'like', $prefix . '%')
            ->orderBy('bill_no', 'desc')
            ->value('bill_no');

        $seq = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $seq = ((int)$m[1]) + 1;
        }
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /** Show simple create form */
    public function create()
    {
        $products = Product::select('id', 'name', 'sku', 'quantity', 'price', 'sale_price')
            ->orderBy('name')
            ->get();

        $productsForPicker = $products->map(function ($p) {
            return [
                'id'         => $p->id,
                'name'       => $p->name,
                'sku'        => $p->sku,
                'quantity'   => (int) $p->quantity,
                'price'      => (float) ($p->price ?? 0),
                'sale_price' => (float) ($p->sale_price ?? 0),
            ];
        })->values();

        return view('backend.billing.create', [
            'products'           => $products,
            'productsForPicker'  => $productsForPicker,
        ]);
    }

    /** Store bill + items, adjust stock, compute P/L */
    public function store(Request $request)
    {
        $data = $request->validate([
            'billing_name'          => ['required', 'string', 'max:150'],
            'pan'                   => ['nullable', 'string', 'max:50'],
            'bill_date_bs'          => ['required', 'string', 'max:15'], // YYYY-MM-DD (BS)
            'address'               => ['nullable', 'string', 'max:255'],
            'remarks'               => ['nullable', 'string'],

            'items'                 => ['required', 'array', 'min:1'],
            'items.*.product_id'    => ['required', 'exists:products,id'],
            'items.*.quantity'      => ['required', 'integer', 'min:1'],
            'items.*.selling_price' => ['required', 'numeric', 'min:0'],
            'items.*.item_remarks'  => ['nullable', 'string'],
        ]);

        $billNo = $this->generateBillNo();

        DB::transaction(function () use ($data, $billNo) {
            $sale = Sale::create([
                'bill_no'      => $billNo,
                'billing_name' => $data['billing_name'],
                'pan'          => $data['pan'] ?? null,
                'bill_date_bs' => $data['bill_date_bs'] ?: null, // save BS date
                'address'      => $data['address'] ?? null,
                'remarks'      => $data['remarks'] ?? null,
                'subtotal'     => 0,
                'total_cost'   => 0,
                'profit'       => 0,
            ]);

            $subtotal = 0;
            $totalCost = 0;

            foreach ($data['items'] as $row) {
                /** @var Product $product */
                $product = Product::lockForUpdate()->findOrFail($row['product_id']);

                $qty       = (int)$row['quantity'];
                $sellPrice = (float)$row['selling_price'];
                $costSnap  = (float)($product->cost_price ?? 0);

                if ($product->quantity < $qty) {
                    // Optional: throw validation here. For now, clamp to available stock:
                    $qty = $product->quantity;
                }

                $lineTotal      = round($qty * $sellPrice, 2);
                $lineCostTotal  = round($qty * $costSnap, 2);
                $lineProfit     = round($lineTotal - $lineCostTotal, 2);

                SaleItem::create([
                    'sale_id'         => $sale->id,
                    'product_id'      => $product->id,
                    'product_name'    => $product->name,
                    'quantity'        => $qty,
                    'selling_price'   => $sellPrice,
                    'cost_price'      => $costSnap,
                    'line_total'      => $lineTotal,
                    'line_cost_total' => $lineCostTotal,
                    'line_profit'     => $lineProfit,
                    'item_remarks'    => $row['item_remarks'] ?? null,
                ]);

                $product->quantity = max(0, (int)$product->quantity - $qty);
                $product->save();

                $subtotal  += $lineTotal;
                $totalCost += $lineCostTotal;
            }

            $sale->update([
                'subtotal'   => round($subtotal, 2),
                'total_cost' => round($totalCost, 2),
                'profit'     => round($subtotal - $totalCost, 2),
            ]);
        });

        return redirect()->route('billing.index')->with('success', 'Bill created successfully.');
    }

    /** List bills (simple) */
    public function index(Request $request)
    {
        $bills = Sale::latest()->paginate(20);
        return view('backend.billing.index', compact('bills'));
    }

    /**
     * Profit & Loss report filtered by BS date range (sales.bill_date_bs).
     * BS date is stored as string YYYY-MM-DD => lexicographic range works.
     */
    public function profitLoss(Request $request)
    {
        $from_bs = $request->get('from_bs');
        $to_bs   = $request->get('to_bs');

        // accept only strict YYYY-MM-DD; otherwise treat as null
        $from_bs = (is_string($from_bs) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_bs)) ? $from_bs : null;
        $to_bs   = (is_string($to_bs)   && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_bs))   ? $to_bs   : null;

        // Pull items with related sale for bill_no and BS date filtering
        $items = SaleItem::query()
            ->with(['product', 'sale'])
            // filter by sales.bill_date_bs range
            ->when($from_bs, fn($q) => $q->whereHas('sale', fn($qq) => $qq->where('bill_date_bs', '>=', $from_bs)))
            ->when($to_bs,   fn($q) => $q->whereHas('sale', fn($qq) => $qq->where('bill_date_bs', '<=', $to_bs)))
            // optionally order by BS date in sale (string desc)
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->orderByDesc('sales.bill_date_bs')
            ->select('sale_items.*') // keep SaleItem models
            ->paginate(25)
            ->appends($request->only('from_bs', 'to_bs'));

        // Totals from page collection (or compute on entire range with separate query if needed)
        $collection   = $items->getCollection();
        $totalSelling = $collection->sum('line_total');
        $totalCost    = $collection->sum('line_cost_total');

        $profitPositive = $collection->where('line_profit', '>', 0)->sum('line_profit');
        $lossAbsolute   = $collection->where('line_profit', '<', 0)->sum(function ($row) {
            return abs($row->line_profit);
        });

        $net = [
            'selling'         => round($totalSelling, 2),
            'cost'            => round($totalCost, 2),
            'profit_positive' => round($profitPositive, 2),
            'loss_absolute'   => round($lossAbsolute, 2),
            'net'             => round($profitPositive - $lossAbsolute, 2),
        ];

        return view('backend.billing.profit_loss', compact('items', 'from_bs', 'to_bs', 'net'));
    }

    public function print(Sale $sale)
    {
        $sale->load('items');
        return view('backend.billing.print', compact('sale'));
    }

    public function inlineUpdate(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'bill_no'       => ['nullable','string','max:100'],
            'billing_name'  => ['required','string','max:255'],
            'pan'           => ['nullable','string','max:50'],
            'address'       => ['nullable','string','max:255'],
            'bill_date_bs'  => ['nullable','string','max:20'], // BS only
        ]);

        $sale->fill([
            'bill_no'      => $data['bill_no'] ?? null,
            'billing_name' => $data['billing_name'],
            'pan'          => $data['pan'] ?? null,
            'address'      => $data['address'] ?? null,
            'bill_date_bs' => $data['bill_date_bs'] ?: null,
        ])->save();

        return back()->with('success', 'Bill updated.');
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->load('items');
            foreach ($sale->items as $it) {
                if ($p = Product::find($it->product_id)) {
                    $p->quantity = (int)$p->quantity + (int)$it->quantity;
                    $p->save();
                }
            }
            $sale->delete();
        });

        return redirect()->route('billing.index')->with('success', 'Bill deleted successfully.');
    }
}
