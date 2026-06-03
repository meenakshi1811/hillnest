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
        'shipping_address', 'city', 'state', 'pincode', 'subtotal', 'shipping_fee',
        'total', 'payment_method', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
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

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800',
            'confirmed', 'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-emerald-100 text-emerald-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-stone-100 text-stone-800',
        };
    }

    public static function generateOrderNumber(): string
    {
        return 'HN-' . strtoupper(substr(uniqid(), -8));
    }
}
