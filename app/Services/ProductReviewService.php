<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductReviewService
{
    public function canReviewOrderItem(User $user, OrderItem $orderItem): bool
    {
        $orderItem->loadMissing('order');

        if (! $orderItem->product_id) {
            return false;
        }

        $order = $orderItem->order;

        if (! $order || $order->user_id !== $user->id) {
            return false;
        }

        if (! $order->isPaid() || $order->status === 'cancelled') {
            return false;
        }

        return true;
    }

    public function store(User $user, OrderItem $orderItem, int $rating, ?string $comment): ProductReview
    {
        if (! $this->canReviewOrderItem($user, $orderItem)) {
            throw ValidationException::withMessages([
                'rating' => 'You cannot review this item yet.',
            ]);
        }

        return DB::transaction(function () use ($user, $orderItem, $rating, $comment) {
            $review = ProductReview::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'order_item_id' => $orderItem->id,
                ],
                [
                    'product_id' => $orderItem->product_id,
                    'order_id' => $orderItem->order_id,
                    'rating' => $rating,
                    'comment' => $comment,
                    'is_approved' => true,
                ]
            );

            $this->syncProductStats($orderItem->product);

            return $review->fresh(['user', 'product']);
        });
    }

    public function syncProductStats(Product $product): void
    {
        $stats = ProductReview::query()
            ->where('product_id', $product->id)
            ->approved()
            ->selectRaw('COUNT(*) as total, AVG(rating) as average')
            ->first();

        $product->update([
            'reviews_count' => (int) ($stats->total ?? 0),
            'average_rating' => round((float) ($stats->average ?? 0), 1),
        ]);
    }

    public function delete(ProductReview $review): void
    {
        DB::transaction(function () use ($review) {
            $product = $review->product;
            $review->delete();
            $this->syncProductStats($product);
        });
    }
}
