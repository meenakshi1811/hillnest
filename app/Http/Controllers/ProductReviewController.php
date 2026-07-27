<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Services\ProductReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductReviewController extends Controller
{
    public function __construct(private ProductReviewService $reviews)
    {
    }

    public function store(Request $request, OrderItem $orderItem): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $review = $this->reviews->store(
                auth()->user(),
                $orderItem,
                $data['rating'],
                $data['comment'] ?? null
            );
        } catch (ValidationException $exception) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => collect($exception->errors())->flatten()->first(),
                    'errors' => $exception->errors(),
                ], 422);
            }

            throw $exception;
        }

        $orderItem->load('product');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your review has been submitted.',
                'item_id' => $orderItem->id,
                'html' => view('account.partials.review-form', [
                    'item' => $orderItem,
                    'review' => $review,
                ])->render(),
            ]);
        }

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }
}
