<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Coupon extends Model
{
    public const TYPES = [
        'fixed' => 'Fixed amount (₹)',
        'percent' => 'Percentage (%)',
    ];

    protected $fillable = [
        'code',
        'user_id',
        'for_all',
        'type',
        'value',
        'expires_at',
        'used_at',
        'order_id',
        'created_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'for_all' => 'boolean',
            'value' => 'decimal:2',
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    public function isUsed(): bool
    {
        if ($this->for_all) {
            return false;
        }

        return $this->used_at !== null;
    }

    public function isRedeemedByUser(User $user): bool
    {
        return $this->redemptions()->where('user_id', $user->id)->exists();
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isAvailable(): bool
    {
        return ! $this->isUsed() && ! $this->isExpired();
    }

    public function isValidForUser(User $user): bool
    {
        if (! $this->isAvailable()) {
            return false;
        }

        if ($this->for_all) {
            return ! $this->isRedeemedByUser($user);
        }

        return (int) $this->user_id === (int) $user->id;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getValueLabelAttribute(): string
    {
        return $this->type === 'percent'
            ? rtrim(rtrim(number_format($this->value, 2), '0'), '.').'%'
            : '₹'.number_format($this->value, 0);
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0;
        }

        $discount = $this->type === 'percent'
            ? round($subtotal * ($this->value / 100), 2)
            : (float) $this->value;

        return min($discount, $subtotal);
    }

    public static function generateCode(): string
    {
        do {
            $code = 'HN-'.strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
