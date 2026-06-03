@extends('layouts.app')

@section('title', 'Shopping Cart — Hillnest')

@section('content')
<section class="py-10 md:py-14 bg-cream min-h-[60vh]">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-3xl md:text-4xl font-semibold text-brand text-center">Shopping Cart</h1>

        @if($items->count())
        <div class="mt-10 grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $item)
                <div class="flex gap-5 bg-white border border-hill-200 p-5 md:p-6">
                    <img src="{{ $item['product']->image_url }}" alt="" class="h-28 w-28 shrink-0 object-cover bg-cream-dark">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg md:text-xl font-semibold text-brand">{{ $item['product']->name }}</h3>
                        <p class="text-base text-brand-light mt-1">{{ $item['product']->size }}</p>
                        <p class="mt-2 text-xl font-bold text-brand">₹{{ number_format($item['product']->price, 0) }}</p>
                        <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="mt-4 flex items-center gap-3">
                            @csrf @method('PATCH')
                            <label class="text-sm font-medium text-brand-light">Qty</label>
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="20" class="w-20 border-2 border-hill-200 py-2 text-center text-base">
                            <button type="submit" class="text-sm font-semibold text-gold hover:underline uppercase tracking-wide">Update</button>
                        </form>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xl font-bold text-brand">₹{{ number_format($item['line_total'], 0) }}</p>
                        <form action="{{ route('cart.remove', $item['product']) }}" method="POST" class="mt-3">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:underline">Remove</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-white border border-hill-200 p-6 md:p-8 h-fit lg:sticky lg:top-28">
                <h2 class="font-display text-2xl font-semibold text-brand">Order Summary</h2>
                <dl class="mt-6 space-y-4 text-base">
                    <div class="flex justify-between"><dt class="text-brand-light">Subtotal</dt><dd class="font-semibold text-brand">₹{{ number_format($subtotal, 0) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-brand-light">Shipping</dt><dd class="font-semibold">{{ $shipping > 0 ? '₹'.number_format($shipping, 0) : 'FREE' }}</dd></div>
                    @if($shipping > 0)
                    <p class="text-sm text-gold">Free shipping on orders above ₹2,000</p>
                    @endif
                    <div class="flex justify-between border-t-2 border-hill-200 pt-4 text-xl"><dt class="font-bold">Total</dt><dd class="font-bold text-brand">₹{{ number_format($total, 0) }}</dd></div>
                </dl>
                <a href="{{ route('checkout.index') }}" class="mt-8 block w-full btn-primary text-center">Check Out</a>
                <a href="{{ route('shop.index') }}" class="mt-4 block text-center text-base text-brand-light hover:text-gold">Continue Shopping</a>
            </div>
        </div>
        @else
        <div class="mt-16 text-center py-20 bg-white border border-hill-200">
            <p class="text-xl text-brand-light">Your cart is currently empty.</p>
            <a href="{{ route('shop.index') }}" class="mt-8 inline-block btn-primary">Browse Ghee</a>
        </div>
        @endif
    </div>
</section>
@endsection
