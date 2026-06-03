@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h1 class="font-display text-2xl font-bold text-stone-800">Earnings Report</h1>
    <form method="GET" class="flex flex-wrap items-end gap-2 text-sm">
        <div>
            <label class="block text-xs text-stone-500 mb-1">From</label>
            <input type="date" name="from" value="{{ $from }}" class="rounded-xl border border-stone-200 px-3 py-2">
        </div>
        <div>
            <label class="block text-xs text-stone-500 mb-1">To</label>
            <input type="date" name="to" value="{{ $to }}" class="rounded-xl border border-stone-200 px-3 py-2">
        </div>
        <button type="submit" class="rounded-xl bg-forest-700 px-4 py-2 text-white font-medium hover:bg-forest-800">Apply</button>
    </form>
</div>

<div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase text-stone-500">Revenue</p>
        <p class="mt-2 text-3xl font-bold text-hill-600">₹{{ number_format($summary['revenue'], 0) }}</p>
    </div>
    <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase text-stone-500">Orders</p>
        <p class="mt-2 text-3xl font-bold text-stone-800">{{ $summary['orders'] }}</p>
    </div>
    <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase text-stone-500">Avg Order Value</p>
        <p class="mt-2 text-3xl font-bold text-stone-800">₹{{ number_format($summary['avg_order'], 0) }}</p>
    </div>
    <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase text-stone-500">Shipping Collected</p>
        <p class="mt-2 text-3xl font-bold text-stone-800">₹{{ number_format($summary['shipping'], 0) }}</p>
    </div>
</div>

<div class="mt-10 grid gap-8 lg:grid-cols-2">
    <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
        <h2 class="font-semibold text-stone-800">Daily Revenue</h2>
        @if($dailyRevenue->count())
        <div class="mt-4 max-h-80 overflow-y-auto space-y-2 text-sm">
            @foreach($dailyRevenue as $day)
            <div class="flex justify-between py-2 border-b border-stone-50">
                <span class="text-stone-600">{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</span>
                <span><strong>₹{{ number_format($day->revenue, 0) }}</strong> <span class="text-stone-400">({{ $day->orders }} orders)</span></span>
            </div>
            @endforeach
        </div>
        @else
        <p class="mt-6 text-stone-400 text-sm">No data for selected period.</p>
        @endif
    </div>

    <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
        <h2 class="font-semibold text-stone-800">Orders by Status</h2>
        <ul class="mt-4 space-y-2 text-sm">
            @foreach($byStatus as $row)
            <li class="flex justify-between py-2 border-b border-stone-50">
                <span class="capitalize">{{ $row->status }}</span>
                <span>{{ $row->count }} orders · ₹{{ number_format($row->revenue ?? 0, 0) }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="mt-8 rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
    <h2 class="font-semibold text-stone-800">Top Products</h2>
    <table class="mt-4 w-full text-sm">
        <thead class="text-left text-xs text-stone-500 uppercase"><tr><th class="py-2">Product</th><th>Units</th><th>Revenue</th></tr></thead>
        <tbody class="divide-y divide-stone-100">
            @forelse($topProducts as $p)
            <tr><td class="py-3">{{ $p->product_name }}</td><td>{{ $p->units_sold }}</td><td class="font-semibold">₹{{ number_format($p->revenue, 0) }}</td></tr>
            @empty
            <tr><td colspan="3" class="py-8 text-center text-stone-400">No sales in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
