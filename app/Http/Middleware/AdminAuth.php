<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated as an admin
        if (!Auth::guard('admin')->check()) {
            return redirect('/admin')->with('error', 'You are not allowed to access this page.');
        }

        return $next($request);
    }
}
