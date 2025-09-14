<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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


    public function dashboard()
    {
        return view('backend.home');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}
