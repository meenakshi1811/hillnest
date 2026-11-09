<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Meta Pixel advanced matching and Conversions API (server-side events).
 *
 * Browser and server send the same event with the same event ID, and Meta keeps
 * one of them. Customer details go out SHA-256 hashed, normalised the way Meta
 * expects, so the browser pixel (which hashes them itself) and the server match.
 */
class MetaConversionsService
{
    public function pixelId(): string
    {
        return (string) config('hillnest.meta.pixel_id');
    }

    public function isEnabled(): bool
    {
        return filled(config('hillnest.meta.pixel_id')) && filled(config('hillnest.meta.capi_token'));
    }

    public function newEventId(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Plain (unhashed) customer details for the browser pixel's advanced matching;
     * the pixel hashes them before sending.
     *
     * @return array<string, string>
     */
    public function browserMatch(?User $user): array
    {
        if (! $user) {
            return [];
        }

        $fields = $this->customerFields($user, $this->latestOrder($user));

        // Pre-hashed so it is identical to the server's hashed value; the pixel leaves hashed values as they are.
        $fields['external_id'] = hash('sha256', $fields['external_id']);

        return $fields;
    }

    /**
     * @param  array<string, mixed>  $customData
     */
    public function send(string $eventName, string $eventId, array $customData = [], ?User $user = null, ?string $sourceUrl = null): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        $user ??= auth()->user();
        $event = $this->event($eventName, $eventId, $customData, $sourceUrl ?? request()->fullUrl());
        $event['user_data'] = $this->hashed($user ? $this->customerFields($user, $this->latestOrder($user)) : []) + $this->browserFields();

        $this->dispatch($event);
    }

    /**
     * Purchase is sent once per order, from whichever happens first: the
     * customer's payment confirmation or Razorpay's webhook.
     */
    public function sendPurchase(Order $order, bool $fromCustomerBrowser): void
    {
        if (! $this->isEnabled() || ! Cache::add('meta-capi-purchase:'.$order->id, true, now()->addDays(30))) {
            return;
        }

        $order->loadMissing('items', 'user');

        $event = $this->event('Purchase', 'order_'.$order->id, [
            'currency' => 'INR',
            'value' => round((float) $order->total, 2),
            'order_id' => $order->order_number,
            'content_type' => 'product',
            'content_ids' => $order->items->pluck('product_id')->filter()->map(fn ($id) => (string) $id)->values()->all(),
            'contents' => $order->items->map(fn ($item) => [
                'id' => (string) $item->product_id,
                'quantity' => (int) $item->quantity,
                'item_price' => round((float) $item->unit_price, 2),
            ])->values()->all(),
        ], route('checkout.success', $order));

        $userData = $this->hashed($this->orderFields($order));

        // Razorpay's webhook comes from Razorpay's servers, not the customer, so
        // its IP address and browser details must not be attached.
        if ($fromCustomerBrowser) {
            $userData += $this->browserFields();
        }

        $event['user_data'] = $userData;

        $this->dispatch($event);
    }

    /** @return array<string, mixed> */
    private function event(string $name, string $eventId, array $customData, string $sourceUrl): array
    {
        return array_filter([
            'event_name' => $name,
            'event_time' => time(),
            'event_id' => $eventId,
            'action_source' => 'website',
            'event_source_url' => $sourceUrl,
            'custom_data' => $customData ?: null,
        ], fn ($value) => $value !== null);
    }

    /**
     * Sent after the response has gone to the visitor, so pages never wait on Meta.
     *
     * @param  array<string, mixed>  $event
     */
    private function dispatch(array $event): void
    {
        $url = sprintf('https://graph.facebook.com/%s/%s/events', config('hillnest.meta.graph_version'), $this->pixelId());
        $payload = array_filter([
            'data' => [$event],
            'test_event_code' => config('hillnest.meta.test_event_code'),
        ]);

        app()->terminating(function () use ($url, $payload, $event) {
            try {
                $response = Http::timeout(5)
                    ->withQueryParameters(['access_token' => config('hillnest.meta.capi_token')])
                    ->post($url, $payload);

                if ($response->failed()) {
                    Log::warning('Meta Conversions API rejected an event', [
                        'event' => $event['event_name'],
                        'status' => $response->status(),
                        'error' => $response->json('error.message'),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning('Meta Conversions API request failed', [
                    'event' => $event['event_name'],
                    'message' => $e->getMessage(),
                ]);
            }
        });
    }

    /** @return array<string, string> */
    private function customerFields(User $user, ?Order $order): array
    {
        return array_filter([
            'em' => $this->normaliseEmail($user->email ?? $order?->customer_email),
            'ph' => $this->normalisePhone($user->phone ?? $order?->customer_phone),
            'fn' => $this->normaliseName(Str::before(trim((string) $user->name), ' ')),
            'ln' => $this->normaliseName(Str::contains(trim((string) $user->name), ' ') ? Str::afterLast(trim((string) $user->name), ' ') : ''),
            'ct' => $this->normalisePlace($order?->city),
            'st' => $this->normalisePlace($order?->state),
            'zp' => $this->normalisePincode($order?->pincode),
            'country' => 'in',
            'external_id' => (string) $user->id,
        ]);
    }

    /** @return array<string, string> */
    private function orderFields(Order $order): array
    {
        $name = trim((string) $order->customer_name);

        return array_filter([
            'em' => $this->normaliseEmail($order->customer_email),
            'ph' => $this->normalisePhone($order->customer_phone),
            'fn' => $this->normaliseName(Str::before($name, ' ')),
            'ln' => $this->normaliseName(Str::contains($name, ' ') ? Str::afterLast($name, ' ') : ''),
            'ct' => $this->normalisePlace($order->city),
            'st' => $this->normalisePlace($order->state),
            'zp' => $this->normalisePincode($order->pincode),
            'country' => 'in',
            'external_id' => $order->user_id ? (string) $order->user_id : null,
        ]);
    }

    /**
     * @param  array<string, string>  $fields
     * @return array<string, list<string>>
     */
    private function hashed(array $fields): array
    {
        return array_map(fn (string $value) => [hash('sha256', $value)], $fields);
    }

    /** @return array<string, string> */
    private function browserFields(): array
    {
        $request = request();
        $fbc = $request->cookie('_fbc');

        // A click from a Meta ad carries fbclid; keep it even before the pixel has set its cookie.
        if (! $fbc && $request->query('fbclid')) {
            $fbc = 'fb.1.'.round(microtime(true) * 1000).'.'.$request->query('fbclid');
        }

        return array_filter([
            'client_ip_address' => $request->ip(),
            'client_user_agent' => $request->userAgent(),
            'fbp' => $request->cookie('_fbp'),
            'fbc' => $fbc,
        ]);
    }

    private function latestOrder(User $user): ?Order
    {
        return Order::where('user_id', $user->id)->latest('id')->first(['id', 'customer_email', 'customer_phone', 'city', 'state', 'pincode']);
    }

    private function normaliseEmail(?string $email): ?string
    {
        $email = Str::lower(trim((string) $email));

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    /** Digits only with the country code, e.g. 919812345678. */
    private function normalisePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);
        $digits = ltrim($digits, '0');

        if (strlen($digits) === 10) {
            $digits = '91'.$digits;
        }

        return strlen($digits) >= 11 ? $digits : null;
    }

    private function normaliseName(?string $name): ?string
    {
        $name = Str::lower(preg_replace('/[^\pL]/u', '', (string) $name));

        return $name !== '' ? $name : null;
    }

    private function normalisePlace(?string $place): ?string
    {
        $place = Str::lower(preg_replace('/[^\pL]/u', '', (string) $place));

        return $place !== '' ? $place : null;
    }

    private function normalisePincode(?string $pincode): ?string
    {
        $pincode = preg_replace('/\D/', '', (string) $pincode);

        return $pincode !== '' ? $pincode : null;
    }
}
