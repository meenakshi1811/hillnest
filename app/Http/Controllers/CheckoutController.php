<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\RazorpayService;
use App\Services\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected CouponService $coupons,
        protected RazorpayService $razorpay,
        protected ShippingService $shipping,
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            if ($order = $this->recentCompletedOrder()) {
                return redirect()->route('checkout.success', $order);
            }

            return redirect()->route('shop.index');
        }

        $summary = $this->orderSummary();
        $shippingMeta = $this->shipping->progressMeta($summary['subtotal']);

        return view('checkout.index', [
            'items' => $this->cart->all(),
            'subtotal' => $summary['subtotal'],
            'shipping' => $summary['shipping'],
            'discount' => $summary['discount'],
            'total' => $summary['total'],
            'appliedCoupon' => $summary['coupon'],
            'user' => auth()->user(),
            'razorpayKey' => $this->razorpay->getKey(),
            'shippingMeta' => $shippingMeta,
            'shippingConfig' => $this->shipping->configForFrontend(),
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

    public function createPayment(Request $request): JsonResponse
    {
        if ($this->cart->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        if (blank($this->razorpay->getKey()) || blank(config('services.razorpay.secret'))) {
            return response()->json(['message' => 'Payment gateway is not configured. Please contact support.'], 503);
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
                return response()->json([
                    'message' => "{$item['product']->name} does not have enough stock.",
                ], 422);
            }
        }

        $coupon = null;
        $discount = 0;

        if ($request->filled('coupon_code')) {
            try {
                $coupon = $this->coupons->validateForUser(auth()->user(), $data['coupon_code']);
                $discount = $coupon->calculateDiscount($this->cart->subtotal());
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json(['errors' => $e->errors()], 422);
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
                return response()->json([
                    'errors' => ['coupon_code' => ['This coupon has already been used.']],
                ], 422);
            }
        }

        try {
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
                    'payment_method' => 'razorpay',
                    'payment_status' => 'pending',
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
                }

                return $order;
            });

            $razorpayOrder = $this->razorpay->createOrder($order);

            $order->update([
                'razorpay_order_id' => $razorpayOrder['id'],
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => (int) round($order->total * 100),
                'currency' => 'INR',
                'razorpay_order_id' => $razorpayOrder['id'],
                'razorpay_key' => $this->razorpay->getKey(),
                'customer' => [
                    'name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'contact' => $order->customer_phone,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Razorpay order creation failed', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Unable to initiate payment. Please try again.',
            ], 500);
        }
    }

    public function verifyPayment(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'order_id' => ['required', 'integer', 'exists:orders,id'],
                'razorpay_order_id' => ['required', 'string'],
                'razorpay_payment_id' => ['required', 'string'],
                'razorpay_signature' => ['required', 'string'],
            ]);

            $order = Order::query()
                ->with('items.product')
                ->where('id', $data['order_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($order->isPaid()) {
                $this->rememberCompletedOrder($order);

                return response()->json([
                    'success' => true,
                    'redirect' => route('checkout.success', $order),
                ]);
            }

            if ($order->razorpay_order_id !== $data['razorpay_order_id']) {
                return response()->json(['message' => 'Invalid payment details.'], 422);
            }

            if (! $this->razorpay->verifySignature(
                $data['razorpay_order_id'],
                $data['razorpay_payment_id'],
                $data['razorpay_signature'],
            )) {
                $order->update([
                    'payment_status' => 'failed',
                    'payment_error' => 'Payment signature verification failed.',
                ]);

                return response()->json(['message' => 'Payment verification failed.'], 422);
            }

            DB::transaction(function () use ($order, $data) {
                $order->refresh();
                $order->loadMissing('items.product', 'coupon');

                if ($order->isPaid()) {
                    return;
                }

                foreach ($order->items as $item) {
                    $item->product?->decrement('stock', $item->quantity);
                }

                if ($order->coupon_id) {
                    $coupon = $order->coupon;

                    if ($coupon && ! $coupon->isUsed()) {
                        $this->coupons->markUsed($coupon, $order);
                    }
                }

                $order->update([
                    'payment_status' => 'paid',
                    'razorpay_payment_id' => $data['razorpay_payment_id'],
                    'paid_at' => now(),
                    'status' => 'confirmed',
                    'payment_error' => null,
                ]);
            });

            $this->cart->clear();
            $this->coupons->clear();
            $this->rememberCompletedOrder($order);

            return response()->json([
                'success' => true,
                'redirect' => route('checkout.success', $order),
            ]);
        } catch (\Throwable $e) {
            Log::error('Payment verification failed', [
                'message' => $e->getMessage(),
                'order_id' => $request->input('order_id'),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Unable to verify payment right now. Please check your orders or contact support.',
            ], 500);
        }
    }

    public function paymentFailed(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'error' => ['nullable', 'string', 'max:500'],
        ]);

        $order = Order::query()
            ->where('id', $data['order_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (! $order->isPaid()) {
            $order->update([
                'payment_status' => 'failed',
                'payment_error' => $data['error'] ?? 'Payment was cancelled or failed.',
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function success(Order $order): View|RedirectResponse
    {
        if ($order->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        if (! $order->isPaid()) {
            return redirect()
                ->route('account.orders.show', $order->order_number)
                ->with('error', 'Payment was not completed for this order.');
        }

        session()->forget('checkout_completed_order_id');
        $order->load('items');

        return view('checkout.success', compact('order'));
    }

    private function rememberCompletedOrder(Order $order): void
    {
        session(['checkout_completed_order_id' => $order->id]);
    }

    private function recentCompletedOrder(): ?Order
    {
        $orderId = session('checkout_completed_order_id');

        if (! $orderId) {
            return null;
        }

        return Order::query()
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->first();
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
