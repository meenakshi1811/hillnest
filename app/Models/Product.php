<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'price', 'compare_price',
        'size', 'category', 'image', 'stock', 'is_active', 'is_featured', 'sort_order',
        'reviews_count', 'badge',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return 'https://images.unsplash.com/photo-1628088062856-eee32a9352e2?w=800&q=80&auto=format&fit=crop';
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return 'https://images.unsplash.com/photo-1628088062856-eee32a9352e2?w=800&q=80&auto=format&fit=crop';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isInStock(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }
}
