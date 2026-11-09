<?php

namespace App\DataTables;

use App\Models\SiteVisit;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class SiteVisitsDataTable
{
    public function query(): Builder
    {
        return SiteVisit::query()->with('user:id,name')->latest('id');
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->filter(function (Builder $query) {
                $search = trim((string) request('search.value'));

                if ($search !== '') {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('ip_address', 'like', "%{$search}%")
                            ->orWhere('source', 'like', "%{$search}%")
                            ->orWhere('landing_path', 'like', "%{$search}%")
                            ->orWhereHas('user', fn (Builder $u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
                    });
                }
            })
            ->addColumn('visitor', fn (SiteVisit $visit) => self::who($visit->user, $visit->ip_address))
            ->addColumn('device_cell', fn (SiteVisit $visit) => e($visit->device ?? '—').'<br><span class="admin-table__muted">'.e($visit->browser ?? '').'</span>')
            ->editColumn('source', fn (SiteVisit $visit) => e($visit->source ?? '—'))
            ->editColumn('landing_path', fn (SiteVisit $visit) => '<code class="admin-code">'.e($visit->landing_path ?? '/').'</code>')
            ->addColumn('time_on_site', fn (SiteVisit $visit) => SiteVisit::formatDuration($visit->durationSeconds()))
            ->addColumn('cart_cell', function (SiteVisit $visit) {
                if ($visit->order_id) {
                    return '<a href="'.route('admin.orders.show', $visit->order_id).'" class="admin-badge admin-badge--active">Ordered</a>';
                }

                return $visit->cart_adds > 0
                    ? '<span class="admin-badge admin-badge--pending">'.$visit->cart_adds.' added, not bought</span>'
                    : '<span class="admin-table__muted">—</span>';
            })
            ->editColumn('created_at', fn (SiteVisit $visit) => $visit->created_at->timezone('Asia/Kolkata')->format('d M, h:i A'))
            ->rawColumns(['visitor', 'device_cell', 'landing_path', 'cart_cell'])
            ->toJson();
    }

    /** Customer name (linked) or "Guest", with the IP address underneath. */
    public static function who($user, ?string $ip): string
    {
        $name = $user
            ? '<a href="'.route('admin.users.show', $user->id).'" class="admin-table__link">'.e($user->name).'</a>'
            : '<span>Guest</span>';

        return $name.'<br><span class="admin-table__muted">'.e($ip ?? '—').'</span>';
    }
}
