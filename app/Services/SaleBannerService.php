<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Carbon;

class SaleBannerService
{
    protected ?int $maxDiscount = null;

    protected Coupon|false|null $coupon = null;

    public function isActive(): bool
    {
        $config = config('hillnest.sale_banner');

        if (! filter_var($config['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }

        $endsAt = $config['ends_at'] ?? null;

        return ! $endsAt || Carbon::now('Asia/Kolkata')->lte(Carbon::parse($endsAt, 'Asia/Kolkata'));
    }

    public function name(): string
    {
        return (string) config('hillnest.sale_banner.name', 'Sale');
    }

    /**
     * Highest saving on an active product versus its MRP, so the banner never
     * promises more than the shop actually offers.
     */
    public function maxDiscountPercent(): int
    {
        return $this->maxDiscount ??= (int) Product::active()
            ->where('stock', '>', 0)
            ->get(['price', 'compare_price'])
            ->max('discount_percent');
    }

    public function coupon(): ?Coupon
    {
        if ($this->coupon === null) {
            $code = config('hillnest.sale_banner.coupon_code');
            $coupon = $code ? Coupon::where('code', strtoupper($code))->first() : null;

            $this->coupon = $coupon && $coupon->for_all && $coupon->isAvailable() ? $coupon : false;
        }

        return $this->coupon ?: null;
    }
}
