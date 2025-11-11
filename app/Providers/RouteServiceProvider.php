<?php

namespace App\Providers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        RateLimiter::for('global', function (Request $request) {
            $ip = $request->ip();

            return [
                Limit::perMinute(30)->by("ip:$ip"),
                Limit::perHour(360)->by("ip:$ip"),
                Limit::perDay(3600)->by("ip:$ip"),
            ];
        });

        RateLimiter::for('login', function (Request $request) {
            $ip = $request->ip();
            $email  = (string) $request->input('email');

            return [
                Limit::perMinute(5)->by("email:$email"),
                Limit::perHour(60)->by("email:$email"),
                Limit::perDay(2000)->by("email:$email"),
            ];
        });
    }
}
