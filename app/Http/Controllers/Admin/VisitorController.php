<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CartEventsDataTable;
use App\DataTables\SiteVisitsDataTable;
use App\Http\Controllers\Controller;
use App\Models\CartEvent;
use App\Models\SiteVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class VisitorController extends Controller
{
    private const TZ = 'Asia/Kolkata';

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $request->get('table') === 'cart'
                ? app(CartEventsDataTable::class)->json()
                : app(SiteVisitsDataTable::class)->json();
        }

        // "Today" and each day follow India time; visits are stored in UTC.
        $now = Carbon::now(self::TZ);
        $since = $now->copy()->subDays(13)->startOfDay();
        $visits = SiteVisit::query()
            ->where('created_at', '>=', $since->copy()->utc())
            ->get(['id', 'visitor_id', 'user_id', 'source', 'page_views', 'cart_adds', 'order_id', 'last_seen_at', 'created_at'])
            ->each(fn (SiteVisit $visit) => $visit->setAttribute('day', $visit->created_at->timezone(self::TZ)->toDateString()));

        $today = $visits->where('day', $now->toDateString());
        $week = $visits->filter(fn (SiteVisit $visit) => $visit->day >= $now->copy()->subDays(6)->toDateString());

        $stats = [
            'today_visitors' => $today->pluck('visitor_id')->unique()->count(),
            'today_customers' => $today->pluck('user_id')->filter()->unique()->count(),
            'week_visitors' => $week->pluck('visitor_id')->unique()->count(),
            'week_customers' => $week->pluck('user_id')->filter()->unique()->count(),
            'avg_time' => SiteVisit::formatDuration($week->avg(fn (SiteVisit $visit) => $visit->durationSeconds()) ?? 0),
            'pages_per_visit' => round($week->avg('page_views') ?? 0, 1),
            'week_visits' => $week->count(),
            'cart_adds' => CartEvent::where('action', 'added')->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay()->utc())->count(),
            'carts_ordered' => $week->where('cart_adds', '>', 0)->whereNotNull('order_id')->count(),
            'carts_started' => $week->where('cart_adds', '>', 0)->count(),
            'meta_ad_visits' => $week->where('source', 'Meta ads')->count(),
        ];

        $daily = collect(range(13, 0))->map(function (int $daysAgo) use ($now, $visits) {
            $date = $now->copy()->subDays($daysAgo);
            $dayVisits = $visits->where('day', $date->toDateString());

            return [
                'label' => $date->format('D, d M'),
                'visitors' => $dayVisits->pluck('visitor_id')->unique()->count(),
                'customers' => $dayVisits->pluck('user_id')->filter()->unique()->count(),
                'avg_time' => SiteVisit::formatDuration($dayVisits->avg(fn (SiteVisit $visit) => $visit->durationSeconds()) ?? 0),
            ];
        });

        $sources = $week->countBy(fn (SiteVisit $visit) => $visit->source ?: 'Direct')->sortDesc()->take(6);

        return view('admin.visitors.index', compact('stats', 'daily', 'sources'));
    }
}
