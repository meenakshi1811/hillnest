<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected ShippingService $shipping,
    ) {}

    public function index()
    {
        $subtotal = $this->cart->subtotal();
        $shippingMeta = $this->shipping->cartSummaryMeta($subtotal);

        return view('cart.index', [
            'items' => $this->cart->all(),
            'subtotal' => $subtotal,
            'shipping' => $shippingMeta['shipping'],
            'total' => $subtotal + $shippingMeta['shipping'],
            'shippingMeta' => $shippingMeta,
            'shippingConfig' => $this->shipping->configForFrontend(),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $qty = (int) ($data['quantity'] ?? 1);

        if (! $product->isInStock($qty)) {
            return $this->respond($request, false, 'Sorry, this product is out of stock.', $product);
        }

        $this->cart->add($product->id, $qty);

        return $this->respond($request, true, 'Added to cart successfully.', $product, 'back');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        if ($data['quantity'] > 0 && ! $product->isInStock($data['quantity'])) {
            return $this->respond($request, false, 'Not enough stock available.', $product, 'cart.index');
        }

        $this->cart->update($product->id, $data['quantity']);

        $message = $data['quantity'] > 0 ? 'Quantity updated.' : 'Removed from cart.';

        return $this->respond($request, true, $message, $product, 'cart.index');
    }

    public function remove(Request $request, Product $product)
    {
        $this->cart->remove($product->id);

        return $this->respond($request, true, 'Removed from cart.', $product, 'cart.index');
    }

    protected function respond(Request $request, bool $success, string $message, Product $product, ?string $redirectRoute = null): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            $status = $success ? 200 : 422;
            $qty = $this->cart->quantity($product->id);
            $subtotal = $this->cart->subtotal();
            $shippingMeta = $this->shipping->cartSummaryMeta($subtotal);

            return response()->json([
                'success' => $success,
                'message' => $message,
                'cart_count' => $this->cart->count(),
                'in_cart' => $this->cart->has($product->id),
                'quantity' => $qty,
                'line_total' => $qty > 0 ? (float) ($product->price * $qty) : 0,
                'unit_price' => (float) $product->price,
                'subtotal' => $subtotal,
                'shipping' => $shippingMeta['shipping'],
                'total' => $subtotal + $shippingMeta['shipping'],
                'product_id' => $product->id,
                'shipping_enabled' => $shippingMeta['shipping_enabled'],
                'has_free_threshold' => $shippingMeta['has_free_threshold'],
                'show_shipping_progress' => $shippingMeta['show_progress'],
                'amount_to_free' => $shippingMeta['amount_to_free'],
                'free_shipping_progress' => $shippingMeta['free_shipping_progress'],
            ], $status);
        }

        if (! $success) {
            return back()->with('error', $message);
        }

        if ($redirectRoute === 'back') {
            return back()->with('success', $message);
        }

        return redirect()->route($redirectRoute)->with('success', $message);
    }
}
