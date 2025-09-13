<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();

            // Check if the user with the same email already exists
            $user = User::where('email', $google_user->getEmail())->first();

            if (!$user) {
                // User does not exist, create a new user
                $user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    'password' => Hash::make(uniqid()) // Generate a dummy password for Google users
                ]);
            } else {
                // User exists, update Google ID if not set
                $user->google_id = $google_user->getId();
                $user->save();
            }

            Auth::login($user);
            return redirect()->intended('/');
        } catch (\Throwable $th) {
            dd('Something went wrong: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'number' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required'
        ]);

        $customer = new User();
        $customer->name = strtoupper($request->input('name'));
        $customer->contact = $request->input('number');
        $customer->email = $request->input('email');
        $customer->password = Hash::make($request->input('password'));

        if ($customer->save()) {
            return back()->with('success', 'You have been registered successfully');
        }

        return back()->withErrors(['error' => 'There was a problem with your registration.'])->withInput();
    }

    public function login()
    {
        return view('frontend.customer.index');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            return redirect()->intended('/');
        }

        return redirect()->back()->withErrors(['error' => 'Invalid credentials.']);
    }

    public function signup()
    {
        return view('frontend.customer.create', ['customer' => User::latest()->get()]);
    }
}