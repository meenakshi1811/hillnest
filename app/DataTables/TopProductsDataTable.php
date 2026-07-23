<?php

namespace App\DataTables;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TopProductsDataTable
{
    public function json(string $from, string $to)
    {
        $rows = DB::table('order_items')
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
            ->get();

        return DataTables::of($rows)
            ->editColumn('revenue', fn ($row) => '₹'.number_format($row->revenue, 0))
            ->toJson();
    }
}
