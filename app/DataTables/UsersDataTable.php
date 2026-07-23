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
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('name_link', fn (User $user) => '<a href="'.route('admin.users.show', $user).'" class="admin-table__link">'.e($user->name).'</a>')
            ->editColumn('created_at', fn (User $user) => $user->created_at->format('d M Y'))
            ->rawColumns(['name_link'])
            ->toJson();
    }
}
