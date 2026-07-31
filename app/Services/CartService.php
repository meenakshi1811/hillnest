<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected const SESSION_KEY = 'hillnest_cart';

    public function __construct(protected ShippingService $shipping) {}

    public function all(): Collection
    {
        $items = collect(session(self::SESSION_KEY, []));
        $productIds = $items->pluck('product_id')->filter()->unique();

        if ($productIds->isEmpty()) {
            return collect();
        }

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        return $items->map(function ($item) use ($products) {
            $product = $products->get($item['product_id']);
            if (! $product) {
                return null;
            }

            $qty = max(1, (int) ($item['quantity'] ?? 1));

            return [
                'product_id' => $product->id,
                'product' => $product,
                'quantity' => $qty,
                'line_total' => $product->price * $qty,
            ];
        })->filter()->values();
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $items = session(self::SESSION_KEY, []);
        $found = false;

        foreach ($items as &$item) {
            if ($item['product_id'] === $productId) {
                $item['quantity'] = ($item['quantity'] ?? 1) + $quantity;
                $found = true;
                break;
            }
        }

        if (! $found) {
            $items[] = ['product_id' => $productId, 'quantity' => $quantity];
        }

        session([self::SESSION_KEY => $items]);
    }

    public function update(int $productId, int $quantity): void
    {
        $items = session(self::SESSION_KEY, []);

        if ($quantity <= 0) {
            $this->remove($productId);

            return;
        }

        foreach ($items as &$item) {
            if ($item['product_id'] === $productId) {
                $item['quantity'] = $quantity;
            }
        }

        session([self::SESSION_KEY => $items]);
    }

    public function remove(int $productId): void
    {
        $items = collect(session(self::SESSION_KEY, []))
            ->reject(fn ($item) => $item['product_id'] === $productId)
            ->values()
            ->all();

        session([self::SESSION_KEY => $items]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function has(int $productId): bool
    {
        return collect(session(self::SESSION_KEY, []))
            ->contains(fn ($item) => ($item['product_id'] ?? null) === $productId);
    }

    public function quantity(int $productId): int
    {
        $item = collect(session(self::SESSION_KEY, []))
            ->first(fn ($item) => ($item['product_id'] ?? null) === $productId);

        return $item ? max(1, (int) ($item['quantity'] ?? 1)) : 0;
    }

    /** @return list<int> */
    public function productIds(): array
    {
        return collect(session(self::SESSION_KEY, []))
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /** @return array<int, int> */
    public function quantities(): array
    {
        return collect(session(self::SESSION_KEY, []))
            ->mapWithKeys(fn ($item) => [
                (int) $item['product_id'] => max(1, (int) ($item['quantity'] ?? 1)),
            ])
            ->all();
    }

    public function count(): int
    {
        return $this->all()->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) $this->all()->sum('line_total');
    }

    public function shippingFee(): float
    {
        return $this->shipping->calculateFee($this->subtotal());
    }

    public function total(): float
    {
        return $this->subtotal() + $this->shippingFee();
    }

    public function isEmpty(): bool
    {
        return $this->all()->isEmpty();
    }
}
