<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrdersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        protected OrderNotificationService $orderNotifications,
    ) {}

    public function index(Request $request, OrdersDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        return view('admin.orders.index');
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Order::STATUSES))],
        ]);

        $previousStatus = $order->status;

        if ($previousStatus === $data['status']) {
            return back()->with('success', 'Order status is already '.$order->status_label.'.');
        }

        $order->update(['status' => $data['status']]);

        try {
            $this->orderNotifications->sendStatusUpdateEmail($order->fresh(['items']), $previousStatus);
        } catch (\Throwable $e) {
            Log::error('Order status update email failed', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            return back()->with('success', 'Order status updated, but the customer notification email could not be sent.');
        }

        return back()->with('success', 'Order status updated and customer notified by email.');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $data = $request->validate([
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'tracking_url' => ['nullable', 'url', 'max:500'],
        ]);

        $order->update([
            'tracking_number' => filled($data['tracking_number'] ?? null) ? trim($data['tracking_number']) : null,
            'tracking_url' => filled($data['tracking_url'] ?? null) ? trim($data['tracking_url']) : null,
        ]);

        return back()->with('success', 'Shipment tracking details saved.');
    }
}
