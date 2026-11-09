<?php

namespace App\Http\Middleware;

use App\Services\VisitTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Counts storefront page views for the admin Visitors page. */
class TrackSiteVisit
{
    public function __construct(private VisitTracker $tracker)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isPageView = $request->isMethod('GET')
            && ! $request->ajax()
            && ! $request->expectsJson()
            && $response->getStatusCode() === 200
            && str_contains((string) $response->headers->get('Content-Type'), 'text/html');

        if ($isPageView) {
            try {
                $this->tracker->recordPageView($request);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $response;
    }
}
