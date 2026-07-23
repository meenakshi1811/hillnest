<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class OrdersDataTable
{
    public function query(): Builder
    {
        $query = Order::query()->latest();

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->filter(function (Builder $query) {
                $search = request('search.value');

                if ($search) {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_email', 'like', "%{$search}%")
                            ->orWhere('coupon_code', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('order_link', fn (Order $order) => '<a href="'.route('admin.orders.show', $order).'" class="admin-table__link">'.$order->order_number.'</a>')
            ->addColumn('coupon_info', function (Order $order) {
                if (! $order->hasCoupon()) {
                    return '<span style="color:var(--text-light)">—</span>';
                }

                return '<div><code class="admin-code">'.e($order->coupon_code).'</code><br><span style="color:var(--text-light);font-size:12px">-₹'.number_format($order->discount_amount, 0).'</span></div>';
            })
            ->addColumn('status_badge', fn (Order $order) => '<span class="status-badge '.$order->status_badge_classes.'">'.$order->status_label.'</span>')
            ->addColumn('payment_badge', fn (Order $order) => '<span class="status-badge '.$order->payment_status_badge_classes.'">'.$order->payment_status_label.'</span>')
            ->editColumn('total', fn (Order $order) => '₹'.number_format($order->total, 0))
            ->editColumn('created_at', fn (Order $order) => $order->created_at->format('d M Y'))
            ->rawColumns(['order_link', 'coupon_info', 'status_badge', 'payment_badge'])
            ->toJson();
    }
}
