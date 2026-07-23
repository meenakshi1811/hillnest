<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DailyRevenueDataTable;
use App\DataTables\OrdersByStatusDataTable;
use App\DataTables\TopProductsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->toDateString());

        if ($request->ajax()) {
            return match ($request->get('table')) {
                'daily' => app(DailyRevenueDataTable::class)->json($from, $to),
                'status' => app(OrdersByStatusDataTable::class)->json($from, $to),
                'products' => app(TopProductsDataTable::class)->json($from, $to),
                default => response()->json(['error' => 'Unknown table'], 400),
            };
        }

        $baseQuery = Order::whereNotIn('status', ['cancelled'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $summary = [
            'revenue' => (clone $baseQuery)->sum('total'),
            'orders' => (clone $baseQuery)->count(),
            'avg_order' => (clone $baseQuery)->avg('total') ?? 0,
            'shipping' => (clone $baseQuery)->sum('shipping_fee'),
        ];

        return view('admin.reports.index', compact('summary', 'from', 'to'));
    }
}
