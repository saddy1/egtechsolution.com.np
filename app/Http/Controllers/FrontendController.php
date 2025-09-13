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
        $search = $request['search'] ?? "";
        $brand = Product::distinct('brand')->pluck('brand');
        $category = Product::distinct('category')->pluck('category');

        // Ensure we always have at least one "NULL" option
        if ($brand->isEmpty()) {
            $brand = collect(['NULL']);
        }

        if ($category->isEmpty()) {
            $category = collect(['NULL']);
        }


        if ($search != "") {
            $banner = Banner::orderBy('created_at', 'desc')->get();
            $products = Product::where('name', 'LIKE', "%$search%")
                ->orWhere('brand', 'LIKE', "%$search%")
                ->orWhere('rate', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%")
                ->orWhere('category', 'LIKE', "%$search%")
                ->paginate(8);
            return view('frontend.search', compact('products', 'banner', 'category', 'search', 'cartCount'));
        } else {
            $products = Product::orderBy('created_at', 'desc')->get();
            $Randproducts = Product::inRandomOrder()->limit(8)->get();
            $banner = Banner::orderBy('created_at', 'desc')->get();
            return view('frontend.index', compact('Randproducts', 'products', 'banner', 'category', 'brand', 'cartCount'));
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
    public function show($name, $id)
    {
        //  $name = str_replace('-', ' ', $name);
        $product = Product::with('specifications')->findOrFail($id);
        $category = Product::distinct('category')->pluck('category');
        return view('frontend.show', compact('product', 'category'));
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
