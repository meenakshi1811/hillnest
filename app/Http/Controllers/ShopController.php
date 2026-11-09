<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\MetaConversionsService;

class ShopController extends Controller
{
    public function index()
    {
        $query = Product::active()->orderBy('sort_order');

        if ($search = trim((string) request('q'))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        return view('shop.index', compact('products'));
    }

    public function show(Product $product, MetaConversionsService $meta)
    {
        abort_unless($product->is_active, 404);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $reviews = $product->approvedReviews()
            ->with('user')
            ->limit(12)
            ->get();

        $metaEventId = $meta->newEventId();
        $meta->send('ViewContent', $metaEventId, [
            'content_type' => 'product',
            'content_ids' => [(string) $product->id],
            'content_name' => $product->name,
            'value' => round((float) $product->price, 2),
            'currency' => 'INR',
        ]);

        return view('shop.show', compact('product', 'related', 'reviews', 'metaEventId'));
    }
}
