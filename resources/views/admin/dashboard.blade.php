@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="font-display text-2xl font-bold text-stone-800">Dashboard</h1>
<p class="text-sm text-stone-500 mt-1">Overview of Hillnest store performance</p>

<div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
    @foreach([
        ['label' => 'Total Revenue', 'value' => '₹'.number_format($stats['total_revenue'], 0), 'color' => 'bg-hill-500'],
        ['label' => 'Total Orders', 'value' => $stats['orders_count'], 'color' => 'bg-forest-600'],
        ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'color' => 'bg-amber-500'],
        ['label' => 'Customers', 'value' => $stats['users_count'], 'color' => 'bg-indigo-500'],
        ['label' => 'Products', 'value' => $stats['products_count'], 'color' => 'bg-stone-600'],
    ] as $card)
    <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
        <div class="h-1 w-12 rounded-full {{ $card['color'] }} mb-4"></div>
        <p class="text-xs font-medium uppercase tracking-wider text-stone-500">{{ $card['label'] }}</p>
        <p class="mt-2 text-2xl font-bold text-stone-800">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="mt-10 grid gap-8 lg:grid-cols-2">
    <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
        <h2 class="font-semibold text-stone-800">Revenue (Last 6 Months)</h2>
        @if($monthlyRevenue->count())
        <div class="mt-6 space-y-3">
            @php $maxRev = $monthlyRevenue->max('revenue') ?: 1; @endphp
            @foreach($monthlyRevenue as $row)
            <div>
                <div class="flex justify-between text-xs text-stone-500 mb-1">
                    <span>{{ \Carbon\Carbon::createFromFormat('Y-m', $row->month)->format('M Y') }}</span>
                    <span>₹{{ number_format($row->revenue, 0) }} · {{ $row->orders }} orders</span>
                </div>
                <div class="h-2 rounded-full bg-stone-100 overflow-hidden">
                    <div class="h-full rounded-full bg-hill-500" style="width: {{ min(100, ($row->revenue / $maxRev) * 100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="mt-6 text-sm text-stone-400">No revenue data yet.</p>
        @endif
    </div>

    <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-stone-800">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-forest-700 hover:underline">View all</a>
        </div>
        <div class="mt-4 divide-y divide-stone-100">
            @forelse($recentOrders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between py-3 hover:bg-stone-50 -mx-2 px-2 rounded-lg transition">
                <div>
                    <p class="text-sm font-medium text-stone-800">{{ $order->order_number }}</p>
                    <p class="text-xs text-stone-500">{{ $order->customer_name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold">₹{{ number_format($order->total, 0) }}</p>
                    <span class="text-xs {{ $order->status_badge_classes }} rounded-full px-2 py-0.5">{{ $order->status_label }}</span>
                </div>
            </a>
            @empty
            <p class="py-8 text-sm text-stone-400 text-center">No orders yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
