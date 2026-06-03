@extends('layouts.app')

@section('title', 'My Orders — Hillnest')

@section('content')
<section class="py-10 md:py-14 bg-cream">
    <div class="mx-auto max-w-4xl px-4 sm:px-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h1 class="font-display text-3xl md:text-4xl font-semibold text-brand">My Orders</h1>
            <a href="{{ route('account.profile') }}" class="text-base font-semibold text-gold hover:underline">Edit Profile</a>
        </div>

        @if($orders->count())
        <div class="mt-10 space-y-4">
            @foreach($orders as $order)
            <a href="{{ route('account.orders.show', $order->order_number) }}" class="block bg-white border border-hill-200 p-6 hover:shadow-md transition">
                <div class="flex flex-wrap justify-between gap-4">
                    <div>
                        <p class="text-lg font-bold text-brand">{{ $order->order_number }}</p>
                        <p class="text-base text-brand-light mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-brand">₹{{ number_format($order->total, 0) }}</p>
                        <span class="inline-block mt-2 text-sm font-semibold {{ $order->status_badge_classes }} px-3 py-1 rounded">{{ $order->status_label }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
        @else
        <div class="mt-16 text-center py-16 bg-white border border-hill-200">
            <p class="text-lg text-brand-light">No orders yet.</p>
            <a href="{{ route('shop.index') }}" class="mt-6 inline-block btn-primary">Start Shopping</a>
        </div>
        @endif
    </div>
</section>
@endsection
