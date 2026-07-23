<?php

namespace App\Services;

use App\Models\Order;
use Razorpay\Api\Api;

class RazorpayService
{
    protected Api $api;

    public function __construct()
    {
        $this->api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret'),
        );
    }

    public function getKey(): string
    {
        return (string) config('services.razorpay.key');
    }

    /** @return array<string, mixed> */
    public function createOrder(Order $order): array
    {
        return $this->api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) round($order->total * 100),
            'currency' => 'INR',
            'notes' => [
                'order_id' => (string) $order->id,
                'order_number' => $order->order_number,
            ],
        ])->toArray();
    }

    public function verifySignature(string $razorpayOrderId, string $razorpayPaymentId, string $signature): bool
    {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $signature,
            ]);

            return true;
        } catch (\Exception) {
            return false;
        }
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $secret = config('services.razorpay.webhook_secret');

        if (blank($secret)) {
            return false;
        }

        try {
            $this->api->utility->verifyWebhookSignature($payload, $signature, $secret);

            return true;
        } catch (\Exception) {
            return false;
        }
    }
}
