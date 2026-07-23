<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrdersByStatusDataTable
{
    public function json(string $from, string $to)
    {
        $rows = Order::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        return DataTables::of($rows)
            ->editColumn('status', fn ($row) => ucfirst($row->status))
            ->addColumn('summary', fn ($row) => $row->count.' orders · ₹'.number_format($row->revenue ?? 0, 0))
            ->toJson();
    }
}
