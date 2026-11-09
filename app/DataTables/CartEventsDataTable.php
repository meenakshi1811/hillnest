<?php

namespace App\DataTables;

use App\Models\CartEvent;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class CartEventsDataTable
{
    /** Pass a customer ID to show only that customer's cart activity. */
    public function __construct(private ?int $userId = null)
    {
    }

    public function query(): Builder
    {
        return CartEvent::query()
            ->with(['user:id,name', 'visit:id,order_id'])
            ->when($this->userId, fn (Builder $q) => $q->where('user_id', $this->userId))
            ->latest('id');
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->filter(function (Builder $query) {
                $search = trim((string) request('search.value'));

                if ($search !== '') {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('ip_address', 'like', "%{$search}%")
                            ->orWhere('product_name', 'like', "%{$search}%")
                            ->orWhereHas('user', fn (Builder $u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
                    });
                }
            })
            ->editColumn('created_at', fn (CartEvent $event) => $event->created_at->timezone('Asia/Kolkata')->format('d M, h:i A'))
            ->addColumn('visitor', fn (CartEvent $event) => SiteVisitsDataTable::who($event->user, $event->ip_address))
            ->editColumn('product_name', fn (CartEvent $event) => e($event->product_name))
            ->addColumn('action_badge', function (CartEvent $event) {
                $class = match ($event->action) {
                    'added' => 'admin-badge--active',
                    'removed' => 'admin-badge--cancelled',
                    default => 'admin-badge--default',
                };

                return '<span class="admin-badge '.$class.'">'.e(CartEvent::ACTIONS[$event->action] ?? $event->action).'</span>';
            })
            ->editColumn('value', fn (CartEvent $event) => $event->action === 'removed' ? '—' : '₹'.number_format((float) $event->value, 0))
            ->addColumn('outcome', function (CartEvent $event) {
                $orderId = $event->visit?->order_id;

                return $orderId
                    ? '<a href="'.route('admin.orders.show', $orderId).'" class="admin-badge admin-badge--active">Ordered</a>'
                    : '<span class="admin-badge admin-badge--pending">Not bought</span>';
            })
            ->rawColumns(['visitor', 'action_badge', 'outcome'])
            ->toJson();
    }
}
