<?php

namespace App\Services;

use App\Mail\PasswordResetCodeMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * 6-digit password reset codes for customers ("web") and admins ("admin").
 * Codes are hashed, expire after 10 minutes and allow 5 wrong tries.
 */
class PasswordResetCodes
{
    public const TTL_MINUTES = 10;
    public const MAX_ATTEMPTS = 5;

    /** Email a fresh code if the account exists. Always silent about whether it exists. */
    public function send(string $guard, string $email): void
    {
        $email = mb_strtolower(trim($email));
        $model = $this->model($guard);
        $account = $model::whereRaw('LOWER(email) = ?', [$email])->first();
        if (!$account) return;

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_codes')->where('guard', $guard)->where('email', $email)->delete();
        DB::table('password_reset_codes')->insert([
            'guard'      => $guard,
            'email'      => $email,
            'code_hash'  => Hash::make($code),
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(self::TTL_MINUTES),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::to($account->email)->send(new PasswordResetCodeMail($code, $account->name ?? '', $guard === 'admin'));
        } catch (\Throwable $e) {
            report($e); // shown to nobody: the page always gives the same answer
        }
    }

    /**
     * Check the code and, if right, set the new password.
     * @return string|null error message, or null on success
     */
    public function reset(string $guard, string $email, string $code, string $newPassword): ?string
    {
        $email = mb_strtolower(trim($email));
        $row = DB::table('password_reset_codes')->where('guard', $guard)->where('email', $email)->latest('id')->first();

        if (!$row || now()->greaterThan($row->expires_at)) {
            return 'This code has expired. Request a new one.';
        }
        if ($row->attempts >= self::MAX_ATTEMPTS) {
            return 'Too many wrong codes. Request a new one.';
        }
        if (!Hash::check(preg_replace('/\D/', '', $code), $row->code_hash)) {
            DB::table('password_reset_codes')->where('id', $row->id)->increment('attempts');
            $left = self::MAX_ATTEMPTS - $row->attempts - 1;
            return $left > 0 ? "That code is wrong. {$left} " . ($left === 1 ? 'try' : 'tries') . ' left.' : 'Too many wrong codes. Request a new one.';
        }

        $model = $this->model($guard);
        $account = $model::whereRaw('LOWER(email) = ?', [$email])->first();
        if (!$account) return 'This code has expired. Request a new one.';

        $account->password = Hash::make($newPassword);
        $account->setRememberToken(\Illuminate\Support\Str::random(60)); // signs out "remember me" sessions
        $account->save();

        DB::table('password_reset_codes')->where('guard', $guard)->where('email', $email)->delete();
        return null;
    }

    private function model(string $guard): string
    {
        return $guard === 'admin' ? \App\Models\Admin::class : \App\Models\User::class;
    }
}
