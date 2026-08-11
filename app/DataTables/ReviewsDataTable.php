<?php

namespace App\DataTables;

use App\Models\ProductReview;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class ReviewsDataTable
{
    public function query(): Builder
    {
        return ProductReview::query()
            ->with(['user', 'product', 'order'])
            ->latest('id');
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->addColumn('product_cell', function (ProductReview $review) {
                $name = e($review->product?->name ?? '—');

                return '<div><strong>'.$name.'</strong></div>';
            })
            ->addColumn('customer', function (ProductReview $review) {
                return '<div><strong>'.e($review->user?->name ?? '—').'</strong><br><span style="color:var(--text-light);font-size:12px">'.e($review->user?->loginIdentifier() ?? '').'</span></div>';
            })
            ->addColumn('rating_stars', function (ProductReview $review) {
                $stars = str_repeat('★', $review->rating).str_repeat('☆', 5 - $review->rating);

                return '<span class="admin-review-stars" aria-label="'.$review->rating.' out of 5 stars">'.$stars.'</span>';
            })
            ->addColumn('comment_cell', function (ProductReview $review) {
                if (! filled($review->comment)) {
                    return '<span style="color:var(--text-light)">—</span>';
                }

                $comment = e($review->comment);

                if (strlen($comment) > 120) {
                    $comment = e(substr($review->comment, 0, 117)).'...';
                }

                return '<span class="admin-review-comment">'.$comment.'</span>';
            })
            ->addColumn('order_link', function (ProductReview $review) {
                if (! $review->order) {
                    return '—';
                }

                return '<a href="'.route('admin.orders.show', $review->order).'" class="admin-table__link">'.e($review->order->order_number).'</a>';
            })
            ->editColumn('created_at', fn (ProductReview $review) => $review->created_at?->format('d M Y, h:i A') ?? '—')
            ->addColumn('action', function (ProductReview $review) {
                $deleteUrl = route('admin.reviews.destroy', $review);
                $label = e($review->product?->name ?? 'this review');

                return '
                    <div class="admin-row-actions">
                        <button type="button" class="admin-icon-btn admin-icon-btn--danger js-review-delete" data-url="'.$deleteUrl.'" data-label="'.$label.'" title="Delete review" aria-label="Delete review">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>';
            })
            ->rawColumns(['product_cell', 'customer', 'rating_stars', 'comment_cell', 'order_link', 'action'])
            ->toJson();
    }
}
