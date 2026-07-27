<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Services\ProductReviewService;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function __construct(private ProductReviewService $reviews)
    {
    }

    public function store(Request $request, OrderItem $orderItem)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->reviews->store(
            auth()->user(),
            $orderItem,
            $data['rating'],
            $data['comment'] ?? null
        );

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }
}
