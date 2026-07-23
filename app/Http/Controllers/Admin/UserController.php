<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserOrdersDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request, UsersDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        return view('admin.users.index');
    }

    public function show(Request $request, User $user)
    {
        abort_if($user->is_admin, 404);

        if ($request->ajax()) {
            return (new UserOrdersDataTable($user))->json();
        }

        return view('admin.users.show', compact('user'));
    }
}
