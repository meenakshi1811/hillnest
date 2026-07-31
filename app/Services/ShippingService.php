<?php

namespace App\Services;

use App\Models\StoreSetting;

class ShippingService
{
    public function isEnabled(): bool
    {
        return StoreSetting::getBool('shipping.enabled', true);
    }

    public function flatFee(): float
    {
        return StoreSetting::getFloat('shipping.fee', 99);
    }

    public function freeThreshold(): float
    {
        return StoreSetting::getFloat('shipping.free_threshold', 2000);
    }

    public function hasFreeThreshold(): bool
    {
        return $this->isEnabled() && $this->freeThreshold() > 0;
    }

    public function calculateFee(float $subtotal): float
    {
        if (! $this->isEnabled() || $subtotal <= 0) {
            return 0;
        }

        if ($this->hasFreeThreshold() && $subtotal >= $this->freeThreshold()) {
            return 0;
        }

        return $this->flatFee();
    }

    /** @return array{amount_to_free: float, free_shipping_progress: float, show_progress: bool} */
    public function progressMeta(float $subtotal): array
    {
        if (! $this->hasFreeThreshold() || $subtotal <= 0) {
            return [
                'amount_to_free' => 0,
                'free_shipping_progress' => 0,
                'show_progress' => false,
            ];
        }

        $threshold = $this->freeThreshold();
        $amountToFree = max(0, $threshold - $subtotal);

        return [
            'amount_to_free' => $amountToFree,
            'free_shipping_progress' => min(100, ($subtotal / $threshold) * 100),
            'show_progress' => $amountToFree > 0,
        ];
    }

    /** @return array<string, mixed> */
    public function configForFrontend(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'fee' => $this->flatFee(),
            'free_threshold' => $this->freeThreshold(),
            'has_free_threshold' => $this->hasFreeThreshold(),
        ];
    }

    /** @return array<string, mixed> */
    public function cartSummaryMeta(float $subtotal): array
    {
        $progress = $this->progressMeta($subtotal);

        return array_merge($progress, [
            'shipping_enabled' => $this->isEnabled(),
            'has_free_threshold' => $this->hasFreeThreshold(),
            'shipping' => $this->calculateFee($subtotal),
        ]);
    }
}
