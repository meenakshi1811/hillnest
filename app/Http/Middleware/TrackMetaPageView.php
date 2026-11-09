<?php

namespace App\Http\Middleware;

use App\Services\MetaConversionsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gives each storefront page view one event ID, used by both the browser pixel
 * and the server-side PageView, so Meta counts the visit once.
 */
class TrackMetaPageView
{
    public function __construct(private MetaConversionsService $meta)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $isPageView = $request->isMethod('GET')
            && ! $request->ajax()
            && ! $request->expectsJson()
            && ! $request->is('admin', 'admin/*');

        $eventId = $isPageView ? $this->meta->newEventId() : null;
        View::share('metaPageEventId', $eventId);

        $response = $next($request);

        if ($eventId && $response->getStatusCode() === 200 && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $this->meta->send('PageView', $eventId);
        }

        return $response;
    }
}
