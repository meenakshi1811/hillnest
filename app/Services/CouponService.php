<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CouponService
{
    protected const SESSION_KEY = 'hillnest_coupon_id';

    public function findByCode(string $code): ?Coupon
    {
        return Coupon::where('code', strtoupper(trim($code)))->first();
    }

    public function validateForUser(User $user, string $code): Coupon
    {
        $coupon = $this->findByCode($code);

        if (! $coupon) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon code is not valid.',
            ]);
        }

        if (! $coupon->for_all && (int) $coupon->user_id !== (int) $user->id) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon is not assigned to your account.',
            ]);
        }

        if ($coupon->for_all && $coupon->isRedeemedByUser($user)) {
            throw ValidationException::withMessages([
                'coupon_code' => 'You have already used this coupon.',
            ]);
        }

        if (! $coupon->for_all && $coupon->isUsed()) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon has already been used.',
            ]);
        }

        if ($coupon->isExpired()) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon has expired.',
            ]);
        }

        return $coupon;
    }

    public function getApplied(): ?Coupon
    {
        $couponId = session(self::SESSION_KEY);

        if (! $couponId) {
            return null;
        }

        $coupon = Coupon::find($couponId);

        if (! $coupon || ! auth()->check() || ! $coupon->isValidForUser(auth()->user())) {
            $this->clear();

            return null;
        }

        return $coupon;
    }

    public function apply(Coupon $coupon): void
    {
        session([self::SESSION_KEY => $coupon->id]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function markUsed(Coupon $coupon, Order $order): void
    {
        if ($coupon->for_all) {
            $coupon->redemptions()->create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'used_at' => now(),
            ]);

            return;
        }

        $coupon->update([
            'used_at' => now(),
            'order_id' => $order->id,
        ]);
    }
}
