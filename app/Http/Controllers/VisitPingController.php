<?php

namespace App\Http\Controllers;

use App\Services\VisitTracker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VisitPingController extends Controller
{
    /** Heartbeat sent every 30 seconds while a storefront tab is open. */
    public function __invoke(Request $request, VisitTracker $tracker): Response
    {
        $tracker->recordHeartbeat($request);

        return response()->noContent();
    }
}
