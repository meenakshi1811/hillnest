<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class ProductFeedController extends Controller
{
    /**
     * Product feed for Google Merchant Center (free listings and Shopping ads).
     */
    public function google(): Response
    {
        $products = Product::active()->orderBy('sort_order')->get();

        return response()
            ->view('feeds.google-merchant', compact('products'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
