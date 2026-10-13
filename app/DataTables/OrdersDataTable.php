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
            ->addColumn('action', function (Order $order) {
                $deleteUrl = route('admin.orders.destroy', $order);

                return '
                    <div class="admin-row-actions">
                        <button type="button" class="admin-icon-btn admin-icon-btn--danger js-order-delete" data-url="'.$deleteUrl.'" data-label="'.e($order->order_number).'" data-paid="'.($order->isPaid() ? '1' : '0').'" title="Delete order" aria-label="Delete order">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>';
            })
            ->rawColumns(['order_link', 'coupon_info', 'status_badge', 'payment_badge', 'action'])
            ->toJson();
    }
}
