<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CouponsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request, CouponsDataTable $dataTable): View|JsonResponse
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::whereNull('used_at')
                ->where(function ($query) {
                    $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->count(),
            'used' => Coupon::whereNotNull('used_at')->count(),
        ];

        $customers = User::where('is_admin', false)->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.coupons.index', compact('stats', 'customers'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validatedCoupon($request);

        Coupon::create([
            ...$data,
            'code' => strtoupper($data['code']),
            'created_by' => $request->user()->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon assigned successfully.',
                'redirect' => route('admin.coupons.index'),
            ]);
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon assigned.');
    }

    public function edit(Coupon $coupon): View
    {
        $customers = User::where('is_admin', false)->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.coupons.edit', compact('coupon', 'customers'));
    }

    public function update(Request $request, Coupon $coupon): JsonResponse|RedirectResponse
    {
        if ($coupon->isUsed()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Used coupons cannot be edited.',
                ], 422);
            }

            return back()->with('error', 'Used coupons cannot be edited.');
        }

        $data = $this->validatedCoupon($request, $coupon);

        $coupon->update([
            ...$data,
            'code' => strtoupper($data['code']),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon updated successfully.',
                'redirect' => route('admin.coupons.index'),
            ]);
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon): JsonResponse
    {
        if ($coupon->isUsed()) {
            return response()->json([
                'message' => 'Used coupons cannot be deleted.',
            ], 422);
        }

        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully.',
        ]);
    }

    private function validatedCoupon(Request $request, ?Coupon $coupon = null): array
    {
        $codeRule = ['required', 'string', 'max:30', 'alpha_dash'];

        if ($coupon) {
            $codeRule[] = 'unique:coupons,code,'.$coupon->id;
        } else {
            $codeRule[] = 'unique:coupons,code';
        }

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'code' => $codeRule,
            'type' => ['required', 'in:'.implode(',', array_keys(Coupon::TYPES))],
            'value' => ['required', 'numeric', 'min:0.01'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'value' => 'Percentage discount cannot exceed 100%.',
            ]);
        }

        $user = User::find($data['user_id']);
        if ($user?->isAdmin()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'user_id' => 'Coupons can only be assigned to customers.',
            ]);
        }

        return $data;
    }
}
