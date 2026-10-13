<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $pages = collect(['home', 'shop.index', 'about', 'contact', 'shipping', 'refund', 'privacy', 'terms'])
            ->map(fn (string $route) => ['loc' => route($route), 'lastmod' => null]);

        $products = Product::active()
            ->orderBy('sort_order')
            ->get(['slug', 'updated_at'])
            ->map(fn (Product $product) => [
                'loc' => route('shop.show', $product),
                'lastmod' => $product->updated_at?->toAtomString(),
            ]);

        return response()
            ->view('sitemap', ['urls' => $pages->concat($products)])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
