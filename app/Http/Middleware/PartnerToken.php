<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Lets a partner site (Broad Tech Infosys) call the API with a shared secret. */
class PartnerToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.partner.token');
        $given    = (string) $request->bearerToken();

        // An unset token must never authenticate anyone.
        if ($expected === '' || ! hash_equals($expected, $given)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
