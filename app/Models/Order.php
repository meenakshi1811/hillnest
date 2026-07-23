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
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'city', 'state', 'pincode', 'coupon_id', 'coupon_code',
        'subtotal', 'shipping_fee', 'discount_amount', 'total', 'payment_method', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
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

    public static function generateOrderNumber(): string
    {
        return 'HN-' . strtoupper(substr(uniqid(), -8));
    }
}
