<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    public const PURCHASED_BY = [
        'meenakshi' => 'Meenakshi',
        'sakshi' => 'Sakshi',
    ];

    protected $fillable = [
        'title',
        'quantity',
        'unit_price',
        'total_amount',
        'purchased_by',
        'purchased_at',
        'notes',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'purchased_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPurchasedByLabelAttribute(): string
    {
        return self::PURCHASED_BY[$this->purchased_by] ?? ucfirst($this->purchased_by);
    }

    public static function calculateTotal(int $quantity, float $unitPrice): float
    {
        return round($quantity * $unitPrice, 2);
    }
}
