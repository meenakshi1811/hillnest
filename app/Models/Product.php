<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'price', 'compare_price',
        'size', 'category', 'image', 'stock', 'is_active', 'is_featured', 'is_bestseller', 'is_trending',
        'sort_order', 'reviews_count', 'badge',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
            'is_trending' => 'boolean',
        ];
    }

    public function getDisplayBadgesAttribute(): array
    {
        $badges = [];

        if ($this->badge) {
            $badges[] = ['label' => $this->badge, 'variant' => 'gold'];
        }

        if ($this->is_bestseller) {
            $badges[] = ['label' => 'Best Seller', 'variant' => 'forest'];
        }

        if ($this->is_trending) {
            $badges[] = ['label' => 'Trending', 'variant' => 'trending'];
        }

        return $badges;
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

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->compare_price || $this->compare_price <= $this->price) {
            return null;
        }

        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->discount_percent !== null && $this->discount_percent > 0;
    }

    public function getFormattedSizeAttribute(): string
    {
        if (! $this->size) {
            return '';
        }

        return (string) preg_replace('/(\d)(gm|kg|ml|l)\b/i', '$1 $2', $this->size);
    }

    public function getCardTitleAttribute(): string
    {
        return $this->formatted_size ?: $this->name;
    }

    public function getCardSubtitleAttribute(): string
    {
        $name = $this->name;

        if ($this->size) {
            $stripped = preg_replace('/\s*[—–-]\s*' . preg_quote($this->size, '/') . '\s*$/iu', '', $name);

            if ($stripped !== '' && $stripped !== $name) {
                return $stripped;
            }
        }

        return 'Pure Bilona Ghee';
    }
}
