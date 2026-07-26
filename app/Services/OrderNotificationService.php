<?php

namespace App\Services;

use App\Mail\NewOrderAdminMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\OrderThankYouMail;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotificationService
{
    public function sendOrderConfirmationEmails(Order $order): void
    {
        $order->refresh()->load('items');

        if (! $order->isPaid()) {
            return;
        }

        if (! Cache::add($this->cacheKey($order), true, now()->addDays(30))) {
            return;
        }

        try {
            Mail::to($order->customer_email)->send(new OrderThankYouMail($order));

            foreach ($this->adminRecipients() as $email) {
                Mail::to($email)->send(new NewOrderAdminMail($order));
            }
        } catch (\Throwable $e) {
            Cache::forget($this->cacheKey($order));

            Log::error('Order confirmation emails failed', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sendStatusUpdateEmail(Order $order, string $previousStatus): void
    {
        $order->loadMissing('items');

        Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order, $previousStatus));
    }

    /** @return list<string> */
    private function adminRecipients(): array
    {
        return config('hillnest.order_notification_emails', []);
    }

    private function cacheKey(Order $order): string
    {
        return 'order-confirmation-emails:'.$order->id;
    }
}
