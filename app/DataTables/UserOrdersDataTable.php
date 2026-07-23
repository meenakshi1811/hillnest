<?php

namespace App\DataTables;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class UserOrdersDataTable
{
    public function __construct(private User $user) {}

    public function query(): Builder
    {
        return Order::query()
            ->where('user_id', $this->user->id)
            ->latest();
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->addColumn('order_link', fn (Order $order) => '<a href="'.route('admin.orders.show', $order).'" class="admin-table__link">'.$order->order_number.'</a>')
            ->addColumn('status_badge', fn (Order $order) => '<span class="status-badge '.$order->status_badge_classes.'">'.$order->status_label.'</span>')
            ->editColumn('total', fn (Order $order) => '₹'.number_format($order->total, 0))
            ->editColumn('created_at', fn (Order $order) => $order->created_at->format('d M Y'))
            ->rawColumns(['order_link', 'status_badge'])
            ->toJson();
    }
}
