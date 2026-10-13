<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
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
        Paginator::defaultView('vendor.pagination.tailwind');

        $this->configureRateLimiting();

        $shareCartData = function ($view) {
            $cart = app(CartService::class);
            $view->with('cartCount', $cart->count());
            $view->with('cartProductIds', $cart->productIds());
            $view->with('cartQuantities', $cart->quantities());
        };

        View::composer(['layouts.app', 'layouts.admin', 'components.*'], $shareCartData);

        View::composer('emails.*', function ($view): void {
            $candidates = ['logo-email.png', 'logo.png', 'logo-email.svg', 'logo.svg'];
            $logoUrl = url('images/logo-email.svg');

            foreach ($candidates as $name) {
                if (file_exists(public_path('images/'.$name))) {
                    $logoUrl = url('images/'.$name);
                    break;
                }
            }

            $view->with('logoUrl', $logoUrl);
            $view->with('shopUrl', route('shop.index'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', fn (Request $request) => [
            Limit::perMinute(5)->by(Str::lower((string) $request->input('email')).'|'.$request->ip()),
            Limit::perMinute(20)->by($request->ip()),
        ]);

        RateLimiter::for('register', fn (Request $request) => Limit::perHour(20)->by($request->ip()));

        RateLimiter::for('password-reset', fn (Request $request) => Limit::perMinutes(15, 5)->by($request->ip()));

        RateLimiter::for('coupon', fn (Request $request) => Limit::perMinute(10)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('contact', fn (Request $request) => Limit::perMinutes(10, 5)->by($request->ip()));
    }
}
