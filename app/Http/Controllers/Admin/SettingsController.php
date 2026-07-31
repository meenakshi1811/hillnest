<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use App\Services\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function shipping(ShippingService $shipping): View
    {
        return view('admin.settings.shipping', [
            'shipping' => [
                'enabled' => $shipping->isEnabled(),
                'fee' => $shipping->flatFee(),
                'free_threshold' => $shipping->freeThreshold(),
            ],
        ]);
    }

    public function updateShipping(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'shipping_enabled' => ['nullable', 'boolean'],
            'shipping_fee' => ['required', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['required', 'numeric', 'min:0'],
        ]);

        StoreSetting::set('shipping.enabled', $request->boolean('shipping_enabled') ? '1' : '0');
        StoreSetting::set('shipping.fee', $data['shipping_fee']);
        StoreSetting::set('shipping.free_threshold', $data['free_shipping_threshold']);

        return redirect()
            ->route('admin.settings.shipping')
            ->with('success', 'Shipping settings updated.');
    }
}
