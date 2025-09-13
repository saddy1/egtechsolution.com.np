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
    $search = trim($request->get('search', ''));

    // Distinct brand and category IDs
    $brand = Product::distinct('brand_id')->pluck('brand_id');
    $category = Product::distinct('category_id')->pluck('category_id');

    if ($brand->isEmpty()) {
        $brand = collect(['NULL']);
    }

    if ($category->isEmpty()) {
        $category = collect(['NULL']);
    }

    if ($search !== '') {
        $banners = Banner::orderBy('created_at', 'desc')->get();
        $products = Product::with(['brand:id,name','category:id,name'])
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%")
            ->orWhereHas('brand', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
            ->orWhereHas('category', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
            ->paginate(8);

        return view('frontend.search', compact(
            'products', 'banners', 'category', 'search', 'cartCount'
        ));
    } else {
        $products = Product::with(['brand:id,name','category:id,name'])
            ->latest()
            ->get();

        $Randproducts = Product::inRandomOrder()->limit(8)->get();

        // ✅ Featured products
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->take(8)
            ->get();

        $banners = Banner::orderBy('created_at', 'desc')->get();

        // ✅ SEO data
        $meta_title = "Buy Electronics, Gadgets, and More | EG Tech Solution";
        $meta_description = "Shop the latest electronics, laptops, mobiles, and gadgets at the best prices. Enjoy discounts, featured deals, and fast delivery from EG Tech Solution.";

        return view('frontend.index', compact(
            'Randproducts', 'products', 'featuredProducts', 'banners',
            'category', 'brand', 'cartCount', 'meta_title', 'meta_description'
        ));
    }
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
        $product = Product::with(['brand:id,name', 'category:id,name', 'specifications'])
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
            ->inRandomOrder()
            ->take(4)
            ->get();

        // SEO
        $meta_title = $product->meta_title ?: "{$product->name} | EG Tech Solution";
        $meta_description = $product->meta_description ?: str($product->description)->limit(155);

        return view('frontend.show', compact(
            'product', 'related', 'maxPerOrder', 'meta_title', 'meta_description'
        ));
    }
    public function shop()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        $Randproducts = Product::inRandomOrder()->limit(8)->get();
        $brand = Product::distinct('brand')->pluck('brand');
        $banner = Banner::orderBy('created_at', 'desc')->get();
        $category = Product::distinct('category')->pluck('category');
        return view('frontend.shop', compact('Randproducts', 'products', 'brand', 'category'));
    }
}
