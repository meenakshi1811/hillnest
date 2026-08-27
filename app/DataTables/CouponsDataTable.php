<?php

namespace App\DataTables;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class CouponsDataTable
{
    public function query(): Builder
    {
        return Coupon::query()
            ->with(['user', 'order'])
            ->withCount('redemptions')
            ->latest('id');
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->addColumn('customer', function (Coupon $coupon) {
                if ($coupon->for_all) {
                    $count = (int) ($coupon->redemptions_count ?? 0);

                    return '<div><strong>All customers</strong><br><span style="color:var(--text-light);font-size:12px">'.$count.' used</span></div>';
                }

                return '<div><strong>'.e($coupon->user?->name ?? '—').'</strong><br><span style="color:var(--text-light);font-size:12px">'.e($coupon->user?->loginIdentifier() ?? '').'</span></div>';
            })
            ->editColumn('code', fn (Coupon $coupon) => '<code class="admin-code">'.e($coupon->code).'</code>')
            ->addColumn('discount', fn (Coupon $coupon) => e($coupon->value_label))
            ->addColumn('status_badge', function (Coupon $coupon) {
                if ($coupon->for_all) {
                    if ($coupon->isExpired()) {
                        return '<span class="admin-badge admin-badge--expired">Expired</span>';
                    }

                    return '<span class="admin-badge admin-badge--active">Active</span>';
                }

                if ($coupon->isUsed()) {
                    return '<span class="admin-badge admin-badge--used">Used</span>';
                }

                if ($coupon->isExpired()) {
                    return '<span class="admin-badge admin-badge--expired">Expired</span>';
                }

                return '<span class="admin-badge admin-badge--active">Active</span>';
            })
            ->editColumn('expires_at', fn (Coupon $coupon) => $coupon->expires_at?->format('d M Y') ?? '—')
            ->addColumn('action', function (Coupon $coupon) {
                if (! $coupon->for_all && $coupon->isUsed()) {
                    $orderLink = $coupon->order
                        ? '<a href="'.route('admin.orders.show', $coupon->order).'" class="admin-inline-link">View order</a>'
                        : '—';

                    return '<div class="admin-row-actions">'.$orderLink.'</div>';
                }

                $editUrl = route('admin.coupons.edit', $coupon);
                $deleteUrl = route('admin.coupons.destroy', $coupon);

                return '
                    <div class="admin-row-actions">
                        <a href="'.$editUrl.'" class="admin-icon-btn" title="Edit coupon" aria-label="Edit coupon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        </a>
                        <button type="button" class="admin-icon-btn admin-icon-btn--danger js-coupon-delete" data-url="'.$deleteUrl.'" data-code="'.e($coupon->code).'" title="Delete coupon" aria-label="Delete coupon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>';
            })
            ->rawColumns(['customer', 'code', 'status_badge', 'action'])
            ->toJson();
    }
}
