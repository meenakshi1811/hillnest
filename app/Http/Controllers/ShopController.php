<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::active()->orderBy('sort_order')->get();

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

        return view('shop.show', compact('product', 'related'));
    }
}
