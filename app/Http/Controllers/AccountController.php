<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function orders()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
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
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
