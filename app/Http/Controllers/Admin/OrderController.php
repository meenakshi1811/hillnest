<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrdersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Order status updated.');
    }
}
