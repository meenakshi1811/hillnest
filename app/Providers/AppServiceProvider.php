<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
}
