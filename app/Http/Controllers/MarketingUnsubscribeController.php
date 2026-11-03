<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MarketingUnsubscribeController extends Controller
{
    /**
     * The link in offer emails opens a confirmation page (so link scanners can't
     * unsubscribe anyone by accident); the button there, and mail apps' one-click
     * unsubscribe, POST to the same signed URL.
     */
    public function __invoke(Request $request, User $user): View|Response
    {
        if ($request->isMethod('post')) {
            if (! $user->marketing_unsubscribed_at) {
                $user->forceFill(['marketing_unsubscribed_at' => now()])->save();
            }

            if (! $request->has('confirm')) {
                return response()->noContent();
            }
        }

        return view('marketing.unsubscribe', [
            'user' => $user,
            'isUnsubscribed' => (bool) $user->marketing_unsubscribed_at,
        ]);
    }
}
