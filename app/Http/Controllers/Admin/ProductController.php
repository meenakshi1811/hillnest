<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('sort_order')->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'size' => ['nullable', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $product->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'price' => $data['price'],
            'compare_price' => $data['compare_price'],
            'size' => $data['size'],
            'stock' => $data['stock'],
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }
}
