<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps form-filling bots out of public forms. Pair it with <x-spam-guard /> inside the form.
 *
 * Bots are told their message was sent, so they do not learn to adapt; real
 * visitors who trip a check by accident get an error and can try again.
 */
class BlockSpam
{
    /** Humans need at least this long to fill in a form. */
    private const MIN_SECONDS = 4;

    private const MAX_SECONDS = 6 * 3600;

    public function handle(Request $request, Closure $next): Response
    {
        // A field hidden from people; only bots fill it in.
        if ($request->filled('website')) {
            return $this->pretendSuccess();
        }

        $age = $this->formAge($request);

        if ($age === null || $age > self::MAX_SECONDS) {
            return back()->withInput()->withErrors(['spam' => 'This form expired. Please send it again.']);
        }

        if ($age < self::MIN_SECONDS) {
            return $this->pretendSuccess();
        }

        if (config('services.turnstile.secret') && ! $this->passesTurnstile($request)) {
            return back()->withInput()->withErrors(['spam' => 'Please complete the "I am human" check and send again.']);
        }

        return $next($request);
    }

    /** Seconds since the form was rendered, or null when the stamp is missing or forged. */
    private function formAge(Request $request): ?int
    {
        try {
            return time() - (int) Crypt::decryptString((string) $request->input('_form_time'));
        } catch (\Throwable) {
            return null;
        }
    }

    private function passesTurnstile(Request $request): bool
    {
        try {
            return (bool) Http::asForm()->timeout(8)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret'   => config('services.turnstile.secret'),
                'response' => $request->input('cf-turnstile-response'),
                'remoteip' => $request->ip(),
            ])->json('success');
        } catch (\Throwable) {
            // Cloudflare being unreachable must not stop customers from contacting us.
            return true;
        }
    }

    private function pretendSuccess(): Response
    {
        return back()->with('success', 'Thank you! Your message has been sent.');
    }
}
