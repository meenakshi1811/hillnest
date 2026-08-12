<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

    /**
     * @param  array<int, UploadedFile>  $images
     */
    public function store(User $user, OrderItem $orderItem, int $rating, ?string $comment, array $images = []): ProductReview
    {
        if (! $this->canReviewOrderItem($user, $orderItem)) {
            throw ValidationException::withMessages([
                'rating' => 'You cannot review this item yet.',
            ]);
        }

        return DB::transaction(function () use ($user, $orderItem, $rating, $comment, $images) {
            $existing = ProductReview::query()
                ->where('user_id', $user->id)
                ->where('order_item_id', $orderItem->id)
                ->first();

            $imagePaths = $existing?->images ?? [];

            if ($images !== []) {
                $this->deleteImageFiles($imagePaths);
                $imagePaths = $this->storeImages($images);
            }

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
                    'images' => $imagePaths !== [] ? $imagePaths : null,
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
            $this->deleteImageFiles($review->images ?? []);
            $review->delete();
            $this->syncProductStats($product);
        });
    }

    /**
     * @param  array<int, UploadedFile>  $files
     * @return list<string>
     */
    private function storeImages(array $files): array
    {
        $directory = public_path('images/reviews');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $paths = [];

        foreach ($files as $file) {
            $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $paths[] = 'images/reviews/'.$filename;
        }

        return $paths;
    }

    /**
     * @param  list<string>|null  $paths
     */
    private function deleteImageFiles(?array $paths): void
    {
        foreach ($paths ?? [] as $path) {
            if (! is_string($path) || $path === '' || str_starts_with($path, 'http')) {
                continue;
            }

            $fullPath = public_path($path);

            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}
