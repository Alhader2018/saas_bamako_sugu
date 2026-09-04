<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        // Rate limiting anti-bruteforce pour login
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = strtolower((string) $request->input('email')) . '|' . $request->ip();
            return Limit::perMinute(5)->by($throttleKey)->response(function () {
                return response()->view('errors.429', [
                    'message' => 'Trop de tentatives de connexion infructueuses. Veuillez patienter une minute avant de réessayer.',
                ], 429);
            });
        });

        // Rate limiting pour le passage de commande
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Gates RBAC
        Gate::define('access-admin', fn ($user) => $user->isStaff());
        Gate::define('manage-orders', fn ($user) => $user->canManageOrders());
        Gate::define('manage-catalog', fn ($user) => $user->canManageCatalog());
        Gate::define('manage-settings', fn ($user) => $user->isAdmin());
    }
}
