<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get();

        return view('home', compact('featuredProducts'));
    }

    public function about()
    {
        return view('about');
    }
}
