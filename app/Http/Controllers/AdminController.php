<?php
namespace App\Http\Controllers;

use App\Models\ProductSell;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        // Check if the admin is already logged in
        if (Auth::guard('admin')->check()) {
            return view('backend.home');
        }

        return view('frontend.admin.index'); // This should be the login page
    }

    public function dashboard(Request $request)
    {
        // Validate the login credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->guard('admin')->attempt($credentials)) {
            return view('backend.home'); 
        }
        return redirect()->back()->withErrors('error', 'Invalid email or password');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin');
    }

    
}
