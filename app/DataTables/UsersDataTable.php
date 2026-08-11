<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class UsersDataTable
{
    public function query(): Builder
    {
        return User::query()
            ->where('is_admin', false)
            ->withCount('orders')
            ->latest();
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->filter(function (Builder $query) {
                $search = request('search.value');

                if ($search) {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('name_link', fn (User $user) => '<a href="'.route('admin.users.show', $user).'" class="admin-table__link">'.e($user->name).'</a>')
            ->editColumn('email', fn (User $user) => e($user->email ?? '—'))
            ->editColumn('phone', fn (User $user) => e($user->phone ?? '—'))
            ->addColumn('status_toggle', function (User $user) {
                $checked = $user->is_blocked ? '' : 'checked';
                $url = route('admin.users.toggle-block', $user);

                return '
                    <label class="admin-toggle" title="'.($user->is_blocked ? 'Blocked — click to unblock' : 'Active — click to block').'">
                        <input type="checkbox" class="admin-toggle__input js-user-block-toggle" data-url="'.$url.'" '.$checked.'>
                        <span class="admin-toggle__track" aria-hidden="true"></span>
                        <span class="admin-toggle__label">'.($user->is_blocked ? 'Blocked' : 'Active').'</span>
                    </label>';
            })
            ->editColumn('created_at', fn (User $user) => $user->created_at->format('d M Y'))
            ->rawColumns(['name_link', 'status_toggle'])
            ->toJson();
    }
}
