<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->toDateString());

        $baseQuery = Order::whereNotIn('status', ['cancelled'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $summary = [
            'revenue' => (clone $baseQuery)->sum('total'),
            'orders' => (clone $baseQuery)->count(),
            'avg_order' => (clone $baseQuery)->avg('total') ?? 0,
            'shipping' => (clone $baseQuery)->sum('shipping_fee'),
        ];

        $byStatus = Order::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('status')
            ->get();

        $dailyRevenue = Order::whereNotIn('status', ['cancelled'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->whereDate('orders.created_at', '>=', $from)
            ->whereDate('orders.created_at', '<=', $to)
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.line_total) as revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact('summary', 'byStatus', 'dailyRevenue', 'topProducts', 'from', 'to'));
    }
}
