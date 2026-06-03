@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<a href="{{ route('admin.products.index') }}" class="text-sm text-forest-700 hover:underline">← Products</a>
<h1 class="font-display mt-2 text-2xl font-bold text-stone-800">Edit: {{ $product->name }}</h1>

<form method="POST" action="{{ route('admin.products.update', $product) }}" class="mt-8 max-w-2xl space-y-5 rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
    @csrf @method('PATCH')
    <div>
        <label class="block text-sm font-medium text-stone-600 mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none focus:border-hill-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-stone-600 mb-1">Short Description</label>
        <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none focus:border-hill-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-stone-600 mb-1">Description</label>
        <textarea name="description" rows="5" required class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none focus:border-hill-500">{{ old('description', $product->description) }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-stone-600 mb-1">Price (₹)</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-600 mb-1">Compare Price</label>
            <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-stone-600 mb-1">Size</label>
            <input type="text" name="size" value="{{ old('size', $product->size) }}" class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-600 mb-1">Stock</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full rounded-xl border border-stone-200 px-4 py-2.5 outline-none">
        </div>
    </div>
    <div class="flex gap-6">
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) class="rounded"> Active</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) class="rounded"> Featured</label>
    </div>
    <button type="submit" class="rounded-xl bg-forest-700 px-6 py-2.5 text-sm font-semibold text-white hover:bg-forest-800">Save Product</button>
</form>
@endsection
