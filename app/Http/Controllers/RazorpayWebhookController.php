<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CouponService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RazorpayWebhookController extends Controller
{
    public function __construct(
        protected RazorpayService $razorpay,
        protected CouponService $coupons,
    ) {}

    public function handle(Request $request): Response
    {
        $signature = $request->header('X-Razorpay-Signature', '');

        if (! $this->razorpay->verifyWebhookSignature($request->getContent(), $signature)) {
            return response('Invalid signature', 400);
        }

        $payload = $request->json()->all();
        $event = $payload['event'] ?? null;

        if ($event === 'payment.captured') {
            $this->handlePaymentCaptured($payload);
        }

        if ($event === 'payment.failed') {
            $this->handlePaymentFailed($payload);
        }

        return response('OK', 200);
    }

    /** @param array<string, mixed> $payload */
    protected function handlePaymentCaptured(array $payload): void
    {
        $payment = $payload['payload']['payment']['entity'] ?? null;

        if (! is_array($payment)) {
            return;
        }

        $razorpayOrderId = $payment['order_id'] ?? null;
        $razorpayPaymentId = $payment['id'] ?? null;

        if (! $razorpayOrderId || ! $razorpayPaymentId) {
            return;
        }

        $order = Order::query()
            ->where('razorpay_order_id', $razorpayOrderId)
            ->first();

        if (! $order || $order->isPaid()) {
            return;
        }

        try {
            DB::transaction(function () use ($order, $razorpayPaymentId) {
                $order->refresh();

                if ($order->isPaid()) {
                    return;
                }

                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product?->decrement('stock', $item->quantity);
                }

                if ($order->coupon_id) {
                    $coupon = $order->coupon;

                    if ($coupon && ! $coupon->isUsed()) {
                        $this->coupons->markUsed($coupon, $order);
                    }
                }

                $order->update([
                    'payment_status' => 'paid',
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'paid_at' => now(),
                    'status' => 'confirmed',
                    'payment_error' => null,
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Razorpay webhook payment.captured failed', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /** @param array<string, mixed> $payload */
    protected function handlePaymentFailed(array $payload): void
    {
        $payment = $payload['payload']['payment']['entity'] ?? null;

        if (! is_array($payment)) {
            return;
        }

        $razorpayOrderId = $payment['order_id'] ?? null;

        if (! $razorpayOrderId) {
            return;
        }

        $order = Order::query()
            ->where('razorpay_order_id', $razorpayOrderId)
            ->first();

        if (! $order || $order->isPaid()) {
            return;
        }

        $order->update([
            'payment_status' => 'failed',
            'payment_error' => $payment['error_description'] ?? 'Payment failed.',
        ]);
    }
}
