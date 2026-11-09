<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartEvent extends Model
{
    public const ACTIONS = [
        'added' => 'Added',
        'updated' => 'Changed qty',
        'removed' => 'Removed',
    ];

    protected $fillable = [
        'site_visit_id',
        'user_id',
        'ip_address',
        'product_id',
        'product_name',
        'action',
        'quantity',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(SiteVisit::class, 'site_visit_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
