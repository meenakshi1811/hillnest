<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected function accountStats($user): array
    {
        return [
            'total_orders' => $user->orders()->where('payment_status', 'paid')->count(),
            'total_spent' => (float) $user->orders()->where('payment_status', 'paid')->sum('total'),
            'delivered' => $user->orders()->where('status', 'delivered')->count(),
        ];
    }

    public function orders()
    {
        $user = auth()->user();

        $orders = $user
            ->orders()
            ->where('payment_status', 'paid')
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        $stats = $this->accountStats($user);

        return view('account.orders', compact('orders', 'stats', 'user'));
    }

    public function orderShow($orderNumber)
    {
        $order = auth()->user()
            ->orders()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('account.order-show', compact('order'));
    }

    public function profile()
    {
        $user = auth()->user();

        return view('account.profile', [
            'user' => $user,
            'stats' => $this->accountStats($user),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'name' => $user->name,
                'phone' => $user->phone,
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
