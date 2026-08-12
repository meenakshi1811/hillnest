<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserOrdersDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function toggleBlock(User $user): JsonResponse
    {
        abort_if($user->is_admin, 403);

        $user->update(['is_blocked' => ! $user->is_blocked]);

        return response()->json([
            'success' => true,
            'message' => $user->is_blocked
                ? 'Customer blocked successfully. They can no longer log in.'
                : 'Customer unblocked successfully. They can log in again.',
            'is_blocked' => $user->is_blocked,
        ]);
    }

    public function impersonate(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403);
        abort_unless($request->user()?->isAdmin(), 403);

        $request->session()->put('impersonator_id', $request->user()->id);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('account.orders')
            ->with('success', 'You are now viewing '.$user->name.'’s account.');
    }
}
