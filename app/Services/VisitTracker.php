<?php

namespace App\Services;

use App\Models\CartEvent;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Records storefront visits and cart activity for the admin Visitors page.
 * A visit ends after 30 minutes without a page view or heartbeat.
 */
class VisitTracker
{
    public const COOKIE = 'hn_vid';

    private const VISIT_TIMEOUT_MINUTES = 30;

    private const BOT_PATTERN = '/bot|crawl|spider|slurp|facebookexternalhit|meta-externalagent|preview|lighthouse|headless|curl|wget|python|httpclient|monitor/i';

    /** Admins, admin impersonation, and bots are not counted. */
    public function shouldTrack(Request $request): bool
    {
        if ($request->is('admin', 'admin/*') || $request->session()->has('impersonator_id')) {
            return false;
        }

        if ($request->user()?->isAdmin()) {
            return false;
        }

        $agent = (string) $request->userAgent();

        return $agent !== '' && ! preg_match(self::BOT_PATTERN, $agent);
    }

    public function recordPageView(Request $request): ?SiteVisit
    {
        if (! $this->shouldTrack($request)) {
            return null;
        }

        $visit = $this->currentVisit($request, true);
        $visit->page_views++;
        $visit->last_seen_at = now();
        $visit->user_id ??= $request->user()?->id;
        $visit->save();

        $this->touchUser($request);

        return $visit;
    }

    /** Heartbeat from an open tab, so time on site includes reading time on the last page. */
    public function recordHeartbeat(Request $request): void
    {
        if (! $this->shouldTrack($request)) {
            return;
        }

        $visit = $this->currentVisit($request, false);

        if ($visit) {
            $visit->update(['last_seen_at' => now(), 'user_id' => $visit->user_id ?? $request->user()?->id]);
            $this->touchUser($request);
        }
    }

    public function recordCartEvent(Request $request, Product $product, string $action, int $quantity): void
    {
        if (! $this->shouldTrack($request)) {
            return;
        }

        $visit = $this->currentVisit($request, true);
        $visit->last_seen_at = now();
        $visit->user_id ??= $request->user()?->id;

        if ($action === 'added') {
            $visit->cart_adds++;
        }

        $visit->save();

        CartEvent::create([
            'site_visit_id' => $visit->id,
            'user_id' => $request->user()?->id,
            'ip_address' => $request->ip(),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'action' => $action,
            'quantity' => $quantity,
            'value' => round((float) $product->price * $quantity, 2),
        ]);
    }

    public function recordOrder(Request $request, Order $order): void
    {
        $visit = $this->currentVisit($request, false);

        if ($visit && ! $visit->order_id) {
            $visit->update(['order_id' => $order->id, 'last_seen_at' => now()]);
        }
    }

    private function currentVisit(Request $request, bool $create): ?SiteVisit
    {
        $visitorId = $request->cookie(self::COOKIE);

        if (! is_string($visitorId) || ! Str::isUuid($visitorId)) {
            if (! $create) {
                return null;
            }

            $visitorId = (string) Str::uuid();
            Cookie::queue(self::COOKIE, $visitorId, 60 * 24 * 365);
        }

        $visit = SiteVisit::query()
            ->where('visitor_id', $visitorId)
            ->where('last_seen_at', '>=', now()->subMinutes(self::VISIT_TIMEOUT_MINUTES))
            ->latest('id')
            ->first();

        if ($visit || ! $create) {
            return $visit;
        }

        return new SiteVisit([
            'visitor_id' => $visitorId,
            'user_id' => $request->user()?->id,
            'ip_address' => $request->ip(),
            'device' => $this->device($request),
            'browser' => $this->browser($request),
            'source' => $this->source($request),
            'landing_path' => Str::limit('/'.ltrim($request->path(), '/'), 250, ''),
            'referrer' => Str::limit((string) $request->headers->get('referer'), 490, '') ?: null,
            'page_views' => 0,
            'cart_adds' => 0,
            'last_seen_at' => now(),
        ]);
    }

    /** Last visit on the customer record, written at most once every 5 minutes. */
    private function touchUser(Request $request): void
    {
        $user = $request->user();

        if ($user && (! $user->last_seen_at || $user->last_seen_at->lt(now()->subMinutes(5)))) {
            $user->forceFill(['last_seen_at' => now()])->saveQuietly();
        }
    }

    private function device(Request $request): string
    {
        $agent = (string) $request->userAgent();

        if (preg_match('/iPad|Tablet/i', $agent)) {
            return 'Tablet';
        }

        return preg_match('/Mobi|Android|iPhone/i', $agent) ? 'Mobile' : 'Desktop';
    }

    private function browser(Request $request): string
    {
        $agent = (string) $request->userAgent();

        return match (true) {
            str_contains($agent, 'Instagram') => 'Instagram app',
            (bool) preg_match('/FBAN|FBAV|FB_IAB/', $agent) => 'Facebook app',
            str_contains($agent, 'SamsungBrowser') => 'Samsung Internet',
            str_contains($agent, 'Edg/') => 'Edge',
            (bool) preg_match('/OPR\/|Opera/', $agent) => 'Opera',
            (bool) preg_match('/CriOS|Chrome\//', $agent) => 'Chrome',
            (bool) preg_match('/FxiOS|Firefox\//', $agent) => 'Firefox',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Other',
        };
    }

    private function source(Request $request): string
    {
        $utm = Str::lower((string) $request->query('utm_source'));
        $referrerHost = Str::lower((string) parse_url((string) $request->headers->get('referer'), PHP_URL_HOST));

        return match (true) {
            $request->has('fbclid') || in_array($utm, ['facebook', 'fb', 'instagram', 'ig', 'meta'], true) => 'Meta ads',
            $request->has('gclid') || $utm === 'google' => 'Google ads',
            $utm !== '' => Str::limit(Str::title($utm), 38, ''),
            $referrerHost === '' => 'Direct',
            str_contains($referrerHost, 'instagram') => 'Instagram',
            str_contains($referrerHost, 'facebook') || str_contains($referrerHost, 'fb.') => 'Facebook',
            str_contains($referrerHost, 'whatsapp') || str_contains($referrerHost, 'wa.me') => 'WhatsApp',
            str_contains($referrerHost, 'google') => 'Google',
            str_contains($referrerHost, 'bing') => 'Bing',
            str_contains($referrerHost, 'hillnest') => 'Direct',
            default => Str::limit($referrerHost, 38, ''),
        };
    }
}
