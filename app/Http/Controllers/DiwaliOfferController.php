<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CouponService;
use App\Services\SaleBannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DiwaliOfferController extends Controller
{
    public function __construct(
        protected SaleBannerService $saleBanner,
        protected CouponService $coupons,
    ) {}

    public function show(): View
    {
        return view('diwali', [
            // Null once the coupon expires or is removed; the page then says the offer has ended.
            'coupon' => $this->saleBanner->coupon(),
            'products' => Product::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function apply(): RedirectResponse
    {
        $coupon = $this->saleBanner->coupon();

        if (! $coupon) {
            return redirect()->route('diwali.offer')->with('error', 'Sorry, this offer has ended.');
        }

        try {
            $this->coupons->validateForUser(auth()->user(), $coupon->code);
        } catch (ValidationException $e) {
            return redirect()->route('diwali.offer')->with('error', collect($e->errors())->flatten()->first());
        }

        $this->coupons->apply($coupon);

        return redirect()
            ->route('shop.index')
            ->with('success', $coupon->code.' is applied! Your '.$coupon->value_label.' discount will show in your cart.');
    }
}
