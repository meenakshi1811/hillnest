<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CartEventsDataTable;
use App\DataTables\UserOrdersDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\SiteVisit;
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
            return $request->get('table') === 'cart'
                ? (new CartEventsDataTable($user->id))->json()
                : (new UserOrdersDataTable($user))->json();
        }

        $visits = SiteVisit::where('user_id', $user->id)->get(['id', 'page_views', 'last_seen_at', 'created_at']);
        $activity = [
            'last_visit' => $user->last_seen_at,
            'visits' => $visits->count(),
            'pages' => (int) $visits->sum('page_views'),
            'time' => SiteVisit::formatDuration($visits->sum(fn (SiteVisit $visit) => $visit->durationSeconds())),
        ];

        return view('admin.users.show', compact('user', 'activity'));
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

        return redirect()->route('account.orders');
    }
}
