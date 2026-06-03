@extends('layouts.app')

@section('title', 'Checkout — Hillnest')

@section('content')
<section class="py-10 md:py-14 bg-cream">
    <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-3xl md:text-4xl font-semibold text-brand text-center">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST" class="mt-10 grid gap-10 lg:grid-cols-5">
            @csrf
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-white border border-hill-200 p-6 md:p-8">
                    <h2 class="font-display text-2xl font-semibold text-brand">Contact & Delivery</h2>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-base font-medium text-brand mb-2">Full Name *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $user?->name) }}" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                            @error('customer_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-base font-medium text-brand mb-2">Email *</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $user?->email) }}" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                        </div>
                        <div>
                            <label class="block text-base font-medium text-brand mb-2">Phone *</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', $user?->phone) }}" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-base font-medium text-brand mb-2">Address *</label>
                            <textarea name="shipping_address" rows="3" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">{{ old('shipping_address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-base font-medium text-brand mb-2">City *</label>
                            <input type="text" name="city" value="{{ old('city') }}" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                        </div>
                        <div>
                            <label class="block text-base font-medium text-brand mb-2">State *</label>
                            <input type="text" name="state" value="{{ old('state', 'Himachal Pradesh') }}" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                        </div>
                        <div>
                            <label class="block text-base font-medium text-brand mb-2">Pincode *</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-base font-medium text-brand mb-2">Order Notes</label>
                            <textarea name="notes" rows="2" class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none" placeholder="Delivery instructions (optional)">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                @guest
                <p class="text-base text-brand-light">Have an account? <a href="{{ route('login') }}" class="font-semibold text-gold hover:underline">Log in</a> to track orders.</p>
                @endguest
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white border border-hill-200 p-6 md:p-8 lg:sticky lg:top-28">
                    <h2 class="font-display text-2xl font-semibold text-brand">Your Order</h2>
                    <ul class="mt-5 space-y-4 text-base border-b border-hill-200 pb-5">
                        @foreach($items as $item)
                        <li class="flex justify-between gap-2">
                            <span class="text-brand-light">{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                            <span class="font-semibold shrink-0">₹{{ number_format($item['line_total'], 0) }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <dl class="mt-5 space-y-3 text-base">
                        <div class="flex justify-between"><dt>Subtotal</dt><dd class="font-semibold">₹{{ number_format($subtotal, 0) }}</dd></div>
                        <div class="flex justify-between"><dt>Shipping</dt><dd class="font-semibold">{{ $shipping > 0 ? '₹'.number_format($shipping, 0) : 'FREE' }}</dd></div>
                        <div class="flex justify-between text-xl font-bold text-brand pt-3 border-t-2 border-hill-200"><dt>Total</dt><dd>₹{{ number_format($total, 0) }}</dd></div>
                    </dl>
                    <p class="mt-5 text-sm text-brand-light bg-cream p-3"><strong>Payment:</strong> Cash on Delivery (COD)</p>
                    <button type="submit" class="mt-6 w-full btn-primary">Place Order</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
