<?php

namespace App\Http\Controllers;

use App\Models\Product;

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

    public function show(Product $product)
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

        return view('shop.show', compact('product', 'related', 'reviews'));
    }
}
