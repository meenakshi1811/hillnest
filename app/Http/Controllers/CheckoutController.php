<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', [
            'items' => $this->cart->all(),
            'subtotal' => $this->cart->subtotal(),
            'shipping' => $this->cart->shippingFee(),
            'total' => $this->cart->total(),
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $items = $this->cart->all();

        foreach ($items as $item) {
            if (! $item['product']->isInStock($item['quantity'])) {
                return back()->with('error', "{$item['product']->name} does not have enough stock.");
            }
        }

        $order = DB::transaction(function () use ($data, $items) {
            $subtotal = $this->cart->subtotal();
            $shipping = $this->cart->shippingFee();

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'total' => $subtotal + $shipping,
                'payment_method' => 'cod',
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_size' => $item['product']->size,
                    'unit_price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
    }

    public function success(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
