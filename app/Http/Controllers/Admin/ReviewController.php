<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ReviewsDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Services\ProductReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(private ProductReviewService $reviews)
    {
    }

    public function index(Request $request, ReviewsDataTable $dataTable): View|JsonResponse
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        $stats = [
            'total' => ProductReview::count(),
            'average' => round((float) ProductReview::avg('rating'), 1),
            'five_star' => ProductReview::where('rating', 5)->count(),
        ];

        return view('admin.reviews.index', compact('stats'));
    }

    public function destroy(ProductReview $review): JsonResponse
    {
        $this->reviews->delete($review);

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.',
        ]);
    }
}
