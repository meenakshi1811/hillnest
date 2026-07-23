<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected CouponService $coupons,
    ) {}

    public function index(): View
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $summary = $this->orderSummary();

        return view('checkout.index', [
            'items' => $this->cart->all(),
            'subtotal' => $summary['subtotal'],
            'shipping' => $summary['shipping'],
            'discount' => $summary['discount'],
            'total' => $summary['total'],
            'appliedCoupon' => $summary['coupon'],
            'user' => auth()->user(),
        ]);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        if ($this->cart->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        $data = $request->validate([
            'coupon_code' => ['required', 'string', 'max:30'],
        ]);

        $coupon = $this->coupons->validateForUser(auth()->user(), $data['coupon_code']);
        $this->coupons->apply($coupon);

        $summary = $this->orderSummary();

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully.',
            'coupon' => [
                'code' => $coupon->code,
                'label' => $coupon->value_label,
            ],
            'summary' => [
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'shipping' => $summary['shipping'],
                'total' => $summary['total'],
            ],
        ]);
    }

    public function removeCoupon(): JsonResponse
    {
        $this->coupons->clear();
        $summary = $this->orderSummary();

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'summary' => [
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'shipping' => $summary['shipping'],
                'total' => $summary['total'],
            ],
        ]);
    }

    public function store(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:500'],
            'coupon_code' => ['nullable', 'string', 'max:30'],
        ]);

        $items = $this->cart->all();

        foreach ($items as $item) {
            if (! $item['product']->isInStock($item['quantity'])) {
                return back()->with('error', "{$item['product']->name} does not have enough stock.");
            }
        }

        $coupon = null;
        $discount = 0;

        if ($request->filled('coupon_code')) {
            try {
                $coupon = $this->coupons->validateForUser(auth()->user(), $data['coupon_code']);
                $discount = $coupon->calculateDiscount($this->cart->subtotal());
            } catch (\Illuminate\Validation\ValidationException $e) {
                return back()->withErrors($e->errors())->withInput();
            }
        } elseif ($applied = $this->coupons->getApplied()) {
            $coupon = $applied;
            $discount = $coupon->calculateDiscount($this->cart->subtotal());
        }

        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shippingFee();
        $total = max(0, $subtotal - $discount + $shipping);

        if ($coupon) {
            $coupon->refresh();

            if ($coupon->isUsed()) {
                return back()->withErrors([
                    'coupon_code' => 'This coupon has already been used.',
                ])->withInput();
            }
        }

        $order = DB::transaction(function () use ($data, $items, $coupon, $subtotal, $shipping, $discount, $total) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'discount_amount' => $discount,
                'total' => $total,
                'payment_method' => 'cod',
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_size' => $item['product']->size,
                    'unit_price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            if ($coupon) {
                $this->coupons->markUsed($coupon, $order);
            }

            return $order;
        });

        $this->cart->clear();
        $this->coupons->clear();

        return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
    }

    public function success(Order $order): View
    {
        if ($order->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    /** @return array{subtotal: float, shipping: float, discount: float, total: float, coupon: ?\App\Models\Coupon} */
    private function orderSummary(): array
    {
        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shippingFee();
        $coupon = $this->coupons->getApplied();
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $total = max(0, $subtotal - $discount + $shipping);

        return compact('subtotal', 'shipping', 'discount', 'total', 'coupon');
    }
}
