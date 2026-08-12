<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        $user = auth()->user();

        $order = $user
            ->orders()
            ->with(['items.product', 'items.review'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $stats = $this->accountStats($user);

        return view('account.order-show', compact('order', 'user', 'stats'));
    }

    public function leaveImpersonation(Request $request): RedirectResponse
    {
        $adminId = $request->session()->pull('impersonator_id');

        if (! $adminId) {
            return redirect()->route('home');
        }

        $admin = User::query()->whereKey($adminId)->where('is_admin', true)->first();

        if (! $admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Unable to return to the admin account. Please log in again.',
            ]);
        }

        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Returned to your admin account.');
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

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        if (! $user->email) {
            $rules['email'] = ['required', 'email', 'max:255', 'unique:users,email'];
        }

        $data = $request->validate($rules);

        $phone = User::normalizePhone($data['phone'] ?? null);
        $email = $user->email;

        if (! $user->email && ! empty($data['email'])) {
            $email = strtolower(trim($data['email']));
        }

        if ($phone && User::where('phone', $phone)->where('id', '!=', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'This phone number is already registered.',
            ]);
        }

        $user->update([
            'name' => $data['name'],
            'email' => $email,
            'phone' => $phone,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
