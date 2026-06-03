@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<h1 class="font-display text-2xl font-bold text-stone-800">Products</h1>

<div class="mt-8 overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-left text-xs uppercase tracking-wider text-stone-500">
            <tr>
                <th class="px-4 py-3">Product</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach($products as $product)
            <tr class="hover:bg-stone-50">
                <td class="px-4 py-3">
                    <p class="font-medium text-stone-800">{{ $product->name }}</p>
                    <p class="text-xs text-stone-500">{{ $product->size }}</p>
                </td>
                <td class="px-4 py-3 font-semibold">₹{{ number_format($product->price, 0) }}</td>
                <td class="px-4 py-3">{{ $product->stock }}</td>
                <td class="px-4 py-3">
                    @if($product->is_active)
                        <span class="text-xs bg-emerald-100 text-emerald-800 rounded-full px-2 py-0.5 font-semibold">Active</span>
                    @else
                        <span class="text-xs bg-stone-100 text-stone-600 rounded-full px-2 py-0.5 font-semibold">Hidden</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-forest-700 hover:underline text-xs font-medium">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection
