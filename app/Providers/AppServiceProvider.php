<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->applyMailSettings();

        Validator::extend('colon_in_specification', function ($attribute, $value, $parameters, $validator) {
            // Check if the specification contains a colon ':'
            return strpos($value, ':') !== false;
        },'The :attribute field should be type of KEY : DESCRIPTION and hit enter to input second line');
        View::composer('frontend.layouts.header', function ($view) {
            if (auth()->check()) {
                $userId = auth()->id();
                $cartCount = Cart::where('user_id', $userId)->count();
            } else {
                $cartCount = 0;
            }

            $view->with('cartCount', $cartCount);
        });
    }

    /** SMTP saved in Admin → Settings overrides the .env values. */
    private function applyMailSettings(): void
    {
        $s = Setting::allValues();
        if (empty($s['mail_host'])) {
            return; // nothing saved yet: keep .env
        }

        config([
            'mail.default'                 => 'smtp',
            'mail.mailers.smtp.host'       => $s['mail_host'],
            'mail.mailers.smtp.port'       => (int) ($s['mail_port'] ?? 587),
            'mail.mailers.smtp.encryption' => ($s['mail_encryption'] ?? 'tls') === 'none' ? null : ($s['mail_encryption'] ?? 'tls'),
            'mail.mailers.smtp.username'   => $s['mail_username'] ?? null,
            'mail.mailers.smtp.password'   => $s['mail_password'] ?? null,
            'mail.from.address'            => $s['mail_from_address'] ?? config('mail.from.address'),
            'mail.from.name'               => $s['mail_from_name'] ?? config('mail.from.name'),
        ]);
    }
}
