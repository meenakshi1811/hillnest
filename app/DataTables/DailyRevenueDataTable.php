<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DailyRevenueDataTable
{
    public function json(string $from, string $to)
    {
        $rows = Order::query()
            ->whereNotIn('status', ['cancelled'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return DataTables::of($rows)
            ->editColumn('date', fn ($row) => \Carbon\Carbon::parse($row->date)->format('d M Y'))
            ->addColumn('summary', fn ($row) => '<strong style="color:var(--forest)">₹'.number_format($row->revenue, 0).'</strong> <span class="admin-table__muted">('.$row->orders.' orders)</span>')
            ->rawColumns(['summary'])
            ->toJson();
    }
}
