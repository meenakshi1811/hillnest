<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Dispatched',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    public const PAYMENT_STATUSES = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ];

    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'city', 'state', 'pincode', 'coupon_id', 'coupon_code',
        'subtotal', 'shipping_fee', 'discount_amount', 'total', 'payment_method',
        'payment_status', 'razorpay_order_id', 'razorpay_payment_id', 'paid_at', 'payment_error',
        'status', 'tracking_number', 'tracking_url', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function hasCoupon(): bool
    {
        return $this->discount_amount > 0 && filled($this->coupon_code);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'status-badge--pending',
            'confirmed' => 'status-badge--confirmed',
            'processing' => 'status-badge--processing',
            'shipped' => 'status-badge--shipped',
            'delivered' => 'status-badge--delivered',
            'cancelled' => 'status-badge--cancelled',
            default => 'status-badge--default',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? ucfirst((string) $this->payment_status);
    }

    public function getPaymentStatusBadgeClassesAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'status-badge--pending',
            'paid' => 'status-badge--confirmed',
            'failed' => 'status-badge--cancelled',
            'refunded' => 'status-badge--default',
            default => 'status-badge--default',
        };
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function hasTracking(): bool
    {
        return filled($this->tracking_number) || filled($this->tracking_url);
    }

    public function canShowTracking(): bool
    {
        return $this->hasTracking() && in_array($this->status, ['shipped', 'delivered'], true);
    }

    /**
     * @return list<array{key: string, label: string, state: string}>
     */
    public function timelineSteps(): array
    {
        $flow = [
            'pending' => 'Order placed',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Dispatched',
            'delivered' => 'Delivered',
        ];

        if ($this->status === 'cancelled') {
            return [
                ['key' => 'pending', 'label' => 'Order placed', 'state' => 'complete'],
                ['key' => 'cancelled', 'label' => 'Cancelled', 'state' => 'current'],
            ];
        }

        $keys = array_keys($flow);
        $currentIndex = array_search($this->status, $keys, true);

        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        $steps = [];

        foreach ($flow as $key => $label) {
            $index = array_search($key, $keys, true);

            if ($index < $currentIndex) {
                $state = 'complete';
            } elseif ($index === $currentIndex) {
                // Final delivery step should show as completed, not "in progress".
                $state = $this->status === 'delivered' ? 'complete' : 'current';
            } else {
                $state = 'upcoming';
            }

            $steps[] = [
                'key' => $key,
                'label' => $label,
                'state' => $state,
            ];
        }

        return $steps;
    }

    public function statusUpdateMessage(): string
    {
        return match ($this->status) {
            'pending' => 'Your order is currently pending. We will keep you updated as it moves forward.',
            'confirmed' => 'Your order has been confirmed. We are preparing your HillNest ghee with care.',
            'processing' => 'Your order is now being processed and will be shipped soon.',
            'shipped' => 'Great news! Your order has been dispatched and is on its way to you.',
            'delivered' => 'Your order has been delivered. We hope you enjoy your pure HillNest ghee!',
            'cancelled' => 'Your order has been cancelled. If this was unexpected, please contact us and we will assist you.',
            default => 'Your order status has been updated.',
        };
    }

    public static function generateOrderNumber(): string
    {
        return 'HN-' . strtoupper(substr(uniqid(), -8));
    }
}
