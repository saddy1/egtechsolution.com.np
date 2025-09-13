<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Cart;

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
}
