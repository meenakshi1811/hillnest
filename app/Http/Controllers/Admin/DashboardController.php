<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RecentOrdersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request, RecentOrdersDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        $stats = [
            'total_revenue' => Order::whereNotIn('status', ['cancelled'])->sum('total'),
            'orders_count' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'users_count' => User::where('is_admin', false)->count(),
            'products_count' => Product::count(),
        ];

        $monthlyRevenue = Order::whereNotIn('status', ['cancelled'])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'monthlyRevenue'));
    }
}
