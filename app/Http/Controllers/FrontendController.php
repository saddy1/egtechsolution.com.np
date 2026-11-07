<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Banner;
use PharIo\Manifest\Email;
use App\Http\Controllers\CategoryController;
use App\Models\Customer;
use Dotenv\Parser\Lines;
use League\CommonMark\Node\Inline\Newline;
use App\Http\Controllers\BackendController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    
    public function getCartCount()
    {
        if (auth()->check()) {
            $userId = auth()->id();
            // Assuming you have a Cart model and cart items are associated with users
            $cartCount = Cart::where('user_id', $userId)->count();
        } else {
            $cartCount = 0;
        }

        return $cartCount;
    }
    public function index(Request $request)
    {
        $cartCount = $this->getCartCount();

        // ----- Inputs -----
        $search  = trim($request->get('search', ''));
        $catIds  = array_map('intval', (array) $request->get('category', []));
        $maxFromDb = (int) Product::max('price') ?: 0;
        $maxPrice  = (int) $request->get('max_price', $maxFromDb);

        $brandIds = array_map('intval', (array) $request->get('brand', []));

        // ----- Filters ON? (the Shop page always shows the product list) -----
        $hasFilters = $request->routeIs('shop') || $search !== '' || !empty($catIds) || !empty($brandIds)
            || $request->filled('max_price');

        // Common data for sidebar
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        // Banners
        $banners = Banner::orderBy('created_at', 'desc')->get();
        $brands = Brand::withCount('products')->orderBy('name')->get();
        $selectedBrandIds = $brandIds;


        if ($hasFilters) {
            // Build filtered products query
            $products = Product::with(['brand:id,name', 'category:id,name'])
                ->when($search !== '', function ($q) use ($search) {
                    $q->where(function ($qq) use ($search) {
                        $qq->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$search}%"));
                    });
                })
                ->when(!empty($catIds), fn($q) => $q->whereIn('category_id', $catIds))
                ->when(!empty($brandIds), fn($q) => $q->whereIn('brand_id', $brandIds))
                ->when($request->filled('max_price'), fn($q) => $q->where('price', '<=', $maxPrice))
                ->orderByDesc('created_at')
                ->paginate(50)
                ->withQueryString();

            // One card per model: of the variants that matched, keep the cheapest.
            $products->setCollection(
                $products->getCollection()
                    ->sortBy(fn ($p) => (float) $p->final_price)
                    ->unique(fn ($p) => $p->variant_group_id ?: 'p'.$p->id)
                    ->sortByDesc('created_at')->values()
            );

            // Render the SEARCH page (sidebar + results)
            return view('frontend.search', [
                'products'            => $products,
                'banners'             => $banners,
                'categories'          => $categories,
                'selectedCategoryIds' => $catIds,
                'brands'          => $brands,
                'selectedBrandIds' => $selectedBrandIds,
                'maxFromDb'           => $maxFromDb,
                'maxPrice'            => $maxPrice,
                'search'              => $search,
                'cartCount'           => $cartCount,
                'meta_title'          => $search !== '' ? "Search results for {$search} | EG Tech Solution" : 'Shop Computers & Electronics | EG Tech Solution',
                'meta_description'    => 'Browse computers, laptops, accessories and electronics from EG Tech Solution in Dhangadhi, Kailali.',
            ]);
        }

        // ----- No filters -> render homepage/index as usual -----
        $products = collect(); // the landing page no longer lists every product; /shopping does
        $Randproducts = Product::listed()->inRandomOrder()->limit(16)->get();
        // One card per model, even when several of its variants are featured (cheapest wins).
        $featuredProducts = Product::where('is_featured', true)->where('is_active', true)->latest()->take(40)->get()
            ->sortBy(fn ($p) => (float) $p->final_price)
            ->unique(fn ($p) => $p->variant_group_id ?: 'p'.$p->id)
            ->sortByDesc('created_at')->take(8)->values();

        // SEO
        $meta_title = "Computer Shop in Dhangadhi | Laptops & Electronics | EG Tech Solution";
        $meta_description = "EG Tech Solution is a computer shop in Dhangadhi, Kailali for laptops, desktop PCs, accessories and electronics with trusted local support.";
        $meta_keywords = "computer shop Dhangadhi, laptop shop Dhangadhi, electronics shop Dhangadhi, computer accessories Kailali, EG Tech Solution";
        // If you still need brand/category ids for homepage:
        $brand = Product::distinct('brand_id')->pluck('brand_id');
        $categoryIds = Product::distinct('category_id')->pluck('category_id');
        $selectedCategoryIds = $selectedCategoryIds ?? (array) request('category', []);

        return view('frontend.index', compact(
            'Randproducts',
            'products',
            'featuredProducts',
            'banners',
            'categoryIds',
            'brands',
            'selectedBrandIds',
            'cartCount',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'maxPrice',
            'maxFromDb',
            'hasFilters',
            'categories',
            'selectedCategoryIds'
        ));
    }




    public function login()
    {
        $brand = Product::distinct('brand')->pluck('brand');
        $category = Product::distinct('category')->pluck('category');

        return view('frontend.customer.index', compact('category'));
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
        echo '<pre>';
        print_r($request->all());
    }
    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = md5($request->input('password'));


        $user = User::where('email', $email)
            ->where('password', $password)
            ->first();

        if ($user) {
            // User is authenticated
            return view('backend.home'); // Replace 'dashboard' with your desired route
        }

        // Authentication failed
        return redirect('/login')->with('error', 'Invalid email or password');
    }



    // app/Http/Controllers/ProductController.php
    public function show(string $slug)
    {
        $product = Product::with(['brand:id,name', 'category:id,name', 'specifications', 'images'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Max per order: up to 5, but not more than stock
        $maxPerOrder = min(5, max(0, (int) $product->quantity));

        // Related products (you may also like): same category, exclude current
        $related = Product::with(['brand:id,name'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            // Its own variants are offered as option buttons, not as "related".
            ->when($product->variant_group_id, fn ($q) => $q->where(fn ($q) => $q
                ->whereNull('variant_group_id')->orWhere('variant_group_id', '!=', $product->variant_group_id)))
            ->listed()
            ->inRandomOrder()
            ->take(4)
            ->get();

        // SEO
        $meta_title = $product->meta_title ?: "{$product->name} | EG Tech Solution";
        $meta_description = $product->meta_description ?: str($product->description)->limit(155);
        $meta_keywords = $product->keywords ?: implode(',', [$product->name, $product->brand->name ?? '', $product->category->name ?? '']);

        $variantCards = $product->variantCards();

        return view('frontend.show', compact(
            'product',
            'variantCards',
            'related',
            'maxPerOrder',
            'meta_title',
            'meta_description',
            'meta_keywords'
        ));
    }
    public function shop(Request $request)
    {
        // The old shop template was built for columns that no longer exist and crashed.
        // The Shop page now uses the same listing (sidebar filters + results) as search.
        return $this->index($request);
    }
}
