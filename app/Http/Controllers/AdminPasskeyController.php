<?php

namespace App\Http\Controllers;

use App\Models\AdminPasskey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use lbuchs\WebAuthn\Binary\ByteBuffer;
use lbuchs\WebAuthn\WebAuthn;

/**
 * Admin passkeys (WebAuthn): log in with fingerprint, Face ID or the phone's screen lock.
 * Works on HTTPS (or localhost). A passkey is tied to the domain it was created on.
 */
class AdminPasskeyController extends Controller
{
    private function webAuthn(Request $request): WebAuthn
    {
        // true = binary values are sent to the browser as base64url strings
        return new WebAuthn('EG Tech Solution Admin', $request->getHost(), null, true);
    }

    private static function b64u(string $bin): string
    {
        return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
    }

    private static function bin(?string $b64u): string
    {
        return ByteBuffer::fromBase64Url((string) $b64u)->getBinaryString();
    }

    // ---------- Register (admin is logged in) ----------

    public function registerOptions(Request $request): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $wa = $this->webAuthn($request);

        $existing = AdminPasskey::where('admin_id', $admin->id)->pluck('credential_id')->map(fn ($id) => self::bin($id))->all();

        $args = $wa->getCreateArgs(
            'admin-' . $admin->id, $admin->email, $admin->name,
            60,          // seconds
            'required',  // discoverable credential: log in without typing an email
            true,        // require fingerprint / face / PIN
            null,        // phone, laptop or security key
            $existing
        );

        $request->session()->put('passkey_register_challenge', $wa->getChallenge()->getBinaryString());
        return response()->json($args);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'              => ['nullable', 'string', 'max:100'],
            'clientDataJSON'    => ['required', 'string'],
            'attestationObject' => ['required', 'string'],
        ]);

        $challenge = $request->session()->pull('passkey_register_challenge');
        if (!$challenge) {
            return response()->json(['message' => 'Your request expired. Try again.'], 422);
        }

        try {
            $result = $this->webAuthn($request)->processCreate(
                self::bin($data['clientDataJSON']), self::bin($data['attestationObject']), $challenge,
                true, true, false
            );
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Could not add the passkey: ' . $e->getMessage()], 422);
        }

        $passkey = AdminPasskey::create([
            'admin_id'      => Auth::guard('admin')->id(),
            'name'          => $data['name'] ?: 'Passkey ' . now()->format('d M Y'),
            'credential_id' => self::b64u($result->credentialId),
            'public_key'    => $result->credentialPublicKey,
            'sign_count'    => (int) $result->signatureCounter,
        ]);

        return response()->json(['message' => 'Passkey added.', 'id' => $passkey->id]);
    }

    public function destroy(AdminPasskey $passkey)
    {
        abort_unless($passkey->admin_id === Auth::guard('admin')->id(), 403);
        $passkey->delete();
        return back()->with('success', 'Passkey removed.');
    }

    // ---------- Log in (guest) ----------

    public function loginOptions(Request $request): JsonResponse
    {
        $wa = $this->webAuthn($request);
        $args = $wa->getGetArgs([], 60, true, true, true, true, true, true);
        $request->session()->put('passkey_login_challenge', $wa->getChallenge()->getBinaryString());
        return response()->json($args);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id'                => ['required', 'string', 'max:1024'],
            'clientDataJSON'    => ['required', 'string'],
            'authenticatorData' => ['required', 'string'],
            'signature'         => ['required', 'string'],
        ]);

        $fail = fn () => response()->json(['message' => 'That passkey was not recognised. Log in with your password.'], 422);

        $challenge = $request->session()->pull('passkey_login_challenge');
        $passkey = AdminPasskey::where('credential_id', $data['id'])->first();
        if (!$challenge || !$passkey) return $fail();

        $wa = $this->webAuthn($request);
        try {
            $wa->processGet(
                self::bin($data['clientDataJSON']), self::bin($data['authenticatorData']), self::bin($data['signature']),
                $passkey->public_key, $challenge, $passkey->sign_count ?: null, true, true
            );
        } catch (\Throwable $e) {
            report($e);
            return $fail();
        }

        $passkey->forceFill(['sign_count' => (int) $wa->getSignatureCounter(), 'last_used_at' => now()])->save();

        $admin = $passkey->admin;
        if (!$admin) return $fail();

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();
        $request->session()->put('admin', $admin); // same as password login

        return response()->json(['redirect' => route('admin.dashboard')]);
    }
}
