<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request, ProductsDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        return view('admin.products.index');
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validatedProduct($request);

        $imagePath = $request->hasFile('image')
            ? $this->storeProductImage($request->file('image'), $data['name'])
            : null;

        Product::create([
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'price' => $data['price'],
            'compare_price' => $data['compare_price'],
            'size' => $data['size'],
            'category' => 'ghee',
            'image' => $imagePath,
            'stock' => $data['stock'],
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'is_bestseller' => $request->boolean('is_bestseller'),
            'is_trending' => $request->boolean('is_trending'),
            'sort_order' => (Product::max('sort_order') ?? 0) + 1,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'redirect' => route('admin.products.index'),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $data = $this->validatedProduct($request);

        $payload = [
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], $product->id),
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'price' => $data['price'],
            'compare_price' => $data['compare_price'],
            'size' => $data['size'],
            'stock' => $data['stock'],
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'is_bestseller' => $request->boolean('is_bestseller'),
            'is_trending' => $request->boolean('is_trending'),
        ];

        if ($request->hasFile('image')) {
            $this->deleteProductImage($product->image);
            $payload['image'] = $this->storeProductImage($request->file('image'), $data['name']);
        }

        $product->update($payload);
        $product->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'image_url' => $product->image_url,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function toggleActive(Product $product): JsonResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        return response()->json([
            'success' => true,
            'message' => $product->is_active ? 'Product activated successfully.' : 'Product hidden from storefront.',
            'is_active' => $product->is_active,
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->deleteProductImage($product->image);
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    private function validatedProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'size' => ['nullable', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_bestseller' => ['boolean'],
            'is_trending' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);
    }

    private function storeProductImage(UploadedFile $file, string $name): string
    {
        $directory = public_path('images/products');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = Str::slug($name).'-'.time().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'images/products/'.$filename;
    }

    private function deleteProductImage(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http')) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
