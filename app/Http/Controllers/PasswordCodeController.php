<?php

namespace App\Http\Controllers;

use App\Services\PasswordResetCodes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

/** "Forgot password" with an emailed 6-digit code, for customers and admins. */
class PasswordCodeController extends Controller
{
    private function guard(Request $request): string
    {
        return $request->routeIs('admin.*') ? 'admin' : 'web';
    }

    private function routes(string $guard): array
    {
        return $guard === 'admin'
            ? ['send' => 'admin.password.email', 'form' => 'admin.password.reset', 'update' => 'admin.password.update', 'request' => 'admin.password.request', 'login' => url('/admin')]
            : ['send' => 'password.email', 'form' => 'password.reset.code', 'update' => 'password.update', 'request' => 'password.request', 'login' => route('login')];
    }

    public function request(Request $request)
    {
        $guard = $this->guard($request);
        return view('frontend.auth.forgot-password', ['guard' => $guard, 'r' => $this->routes($guard)]);
    }

    public function send(Request $request, PasswordResetCodes $codes)
    {
        $guard = $this->guard($request);
        $data = $request->validate(['email' => ['required', 'email', 'max:150']]);

        $codes->send($guard, $data['email']);
        $request->session()->put("reset_email_{$guard}", $data['email']);

        // Same answer whether or not the account exists
        return redirect()->route($this->routes($guard)['form'])
            ->with('status', 'If an account exists for ' . $data['email'] . ', we have emailed a 6-digit code. Check your inbox and spam folder.');
    }

    public function form(Request $request)
    {
        $guard = $this->guard($request);
        $email = $request->session()->get("reset_email_{$guard}");
        if (!$email) {
            return redirect()->route($this->routes($guard)['request']);
        }
        return view('frontend.auth.reset-code', ['guard' => $guard, 'r' => $this->routes($guard), 'email' => $email]);
    }

    public function update(Request $request, PasswordResetCodes $codes)
    {
        $guard = $this->guard($request);
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'code'     => ['required', 'string', 'max:12'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($error = $codes->reset($guard, $data['email'], $data['code'], $data['password'])) {
            return back()->withErrors(['code' => $error]);
        }

        $request->session()->forget("reset_email_{$guard}");
        $to = $guard === 'admin' ? redirect('/admin') : redirect()->route('login');
        return $to->with('status', 'Your password has been changed. Log in with your new password.');
    }
}
