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

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    public function termsOfUse()
    {
        return view('terms-of-use');
    }

    public function refundPolicy()
    {
        return view('refund-policy');
    }
}
