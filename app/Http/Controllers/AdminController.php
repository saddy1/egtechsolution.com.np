<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Services\OpenAIService;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use OpenAI;

class AdminController extends Controller
{
    public function index()
    {
        // If already logged in, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('frontend.admin.index'); // Admin login page
    }

public function login(Request $request)
{
    // Validate credentials
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate(); // Prevent session fixation

        // ✅ Store admin user in session
        $admin = Auth::guard('admin')->user();
        $request->session()->put('admin', $admin);

        return redirect()->route('admin.dashboard');
    }

    return back()->with('error', 'Invalid email or password');
}


    public function dashboard(Request $request)
    {
        $from = $request->date('from');
        $to   = $request->date('to');

        // KPIs
        $totalSalesAmount = Sale::when($from, fn($q)=>$q->whereDate('created_at','>=',$from))
            ->when($to,   fn($q)=>$q->whereDate('created_at','<=',$to))
            ->sum('subtotal');

        $totalOrders = Sale::when($from, fn($q)=>$q->whereDate('created_at','>=',$from))
            ->when($to,   fn($q)=>$q->whereDate('created_at','<=',$to))
            ->count();

        $productsCount = Product::count();
        $customersCount = User::count(); // if you use User as customers; adjust if you have a Customer model

        // Low stock / out of stock
        $lowStockThreshold = 5;
        $lowStock = Product::where('quantity', '<=', $lowStockThreshold)->orderBy('quantity')->limit(6)->get();
        $inStock  = Product::where('quantity', '>', 0)->count();
        $outStock = Product::where('quantity', '=', 0)->count();

        // Monthly Sales Trend (last 12 months)
        $months = collect(range(0, 11))
            ->map(fn($i) => now()->startOfMonth()->subMonths(11 - $i));
        $labels = $months->map(fn($d)=>$d->format('M Y'))->values();

        $monthly = Sale::selectRaw('DATE_FORMAT(created_at, "%Y-%m") ym, SUM(subtotal) sum_subtotal, COUNT(*) cnt')
            ->where('created_at', '>=', $months->first()->startOfMonth())
            ->groupBy('ym')->orderBy('ym')->get()->keyBy('ym');

        $lineData = $months->map(function ($m) use ($monthly) {
            $key = $m->format('Y-m');
            return [
                'sum' => (float)($monthly[$key]->sum_subtotal ?? 0),
                'cnt' => (int)($monthly[$key]->cnt ?? 0),
            ];
        });

        // Top categories by revenue (if you have category_id on products)
        $categoryRows = SaleItem::query()
            ->select('products.category_id', DB::raw('SUM(sale_items.line_total) as total'))
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->when($from, fn($q)=>$q->whereHas('sale', fn($s)=>$s->whereDate('created_at','>=',$from)))
            ->when($to,   fn($q)=>$q->whereHas('sale', fn($s)=>$s->whereDate('created_at','<=',$to)))
            ->groupBy('products.category_id')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $categoryLabels = [];
        $categoryTotals = [];
        if ($categoryRows->isNotEmpty()) {
            $cats = Category::whereIn('id', $categoryRows->pluck('category_id'))->pluck('name','id');
            foreach ($categoryRows as $r) {
                $categoryLabels[] = $cats[$r->category_id] ?? 'Unknown';
                $categoryTotals[] = (float)$r->total;
            }
        }

        // Top products by revenue
        $topProducts = SaleItem::select('product_name', DB::raw('SUM(line_total) as revenue'))
            ->when($from, fn($q)=>$q->whereHas('sale', fn($s)=>$s->whereDate('created_at','>=',$from)))
            ->when($to,   fn($q)=>$q->whereHas('sale', fn($s)=>$s->whereDate('created_at','<=',$to)))
            ->groupBy('product_name')->orderByDesc('revenue')->limit(8)->get();

        $topProdLabels = $topProducts->pluck('product_name');
        $topProdData   = $topProducts->pluck('revenue')->map(fn($v)=>(float)$v);

        // Recent bills (orders)
        $recentBills = Sale::latest()->limit(8)->get();

        // Trend %s (last month vs previous)
        $thisMonth = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd   = now()->subMonth()->endOfMonth();
        $sumLast = Sale::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('subtotal');
        $sumThis = Sale::where('created_at', '>=', $thisMonth)->sum('subtotal');
        $salesMoM = $sumLast > 0 ? round((($sumThis - $sumLast) / $sumLast) * 100, 1) : 0;

        $ordersLast = Sale::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $ordersThis = Sale::where('created_at', '>=', $thisMonth)->count();
        $ordersMoM = $ordersLast > 0 ? round((($ordersThis - $ordersLast) / $ordersLast) * 100, 1) : 0;

        return view('backend.home', [
            'filters' => ['from'=>$from, 'to'=>$to],
            'kpis' => [
                'sales'    => (float)$totalSalesAmount,
                'orders'   => (int)$totalOrders,
                'products' => (int)$productsCount,
                'customers'=> (int)$customersCount,
                'sales_mom'=> $salesMoM,
                'orders_mom'=> $ordersMoM,
            ],
            'stock' => [
                'low'  => $lowStock,
                'in'   => $inStock,
                'out'  => $outStock,
            ],
            'trend' => [
                'labels' => $labels,
                'sales'  => $lineData->pluck('sum')->values(),
                'orders' => $lineData->pluck('cnt')->values(),
            ],
            'categories' => [
                'labels' => $categoryLabels,
                'values' => $categoryTotals,
            ],
            'topProducts' => [
                'labels' => $topProdLabels,
                'values' => $topProdData,
            ],
            'recentBills' => $recentBills,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin');
    }


    public function generateSeo(Request $request, OpenAIService $aiService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'features' => 'nullable|string|max:5000',
        ]);

        try {
            $seo = $aiService->generateSeoData(
                $validated['name'],
                $validated['category'] ?? 'General',
                $validated['features'] ?? ''
            );
            return response()->json($seo);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to generate SEO.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 422);
        }
    }

}
