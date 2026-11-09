<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\MetaConversionsService;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected ShippingService $shipping,
        protected CouponService $coupons,
        protected MetaConversionsService $meta,
    ) {}

    public function index()
    {
        $subtotal = $this->cart->subtotal();
        $shippingMeta = $this->shipping->cartSummaryMeta($subtotal);
        $appliedCoupon = $this->coupons->getApplied();
        $discount = $appliedCoupon ? $appliedCoupon->calculateDiscount($subtotal) : 0;

        return view('cart.index', [
            'items' => $this->cart->all(),
            'subtotal' => $subtotal,
            'shipping' => $shippingMeta['shipping'],
            'discount' => $discount,
            'total' => max(0, $subtotal - $discount + $shippingMeta['shipping']),
            'appliedCoupon' => $appliedCoupon,
            'availableCoupons' => $this->coupons->availableFor(auth()->user()),
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

        $metaEventId = $this->meta->newEventId();
        $this->meta->send('AddToCart', $metaEventId, [
            'content_type' => 'product',
            'content_ids' => [(string) $product->id],
            'content_name' => $product->name,
            'contents' => [['id' => (string) $product->id, 'quantity' => $qty, 'item_price' => round((float) $product->price, 2)]],
            'value' => round((float) $product->price * $qty, 2),
            'currency' => 'INR',
        ], null, url()->previous());

        return $this->respond($request, true, 'Added to cart successfully.', $product, 'back', ['meta_event_id' => $metaEventId]);
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

    protected function respond(Request $request, bool $success, string $message, Product $product, ?string $redirectRoute = null, array $extra = []): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            $status = $success ? 200 : 422;
            $qty = $this->cart->quantity($product->id);
            $subtotal = $this->cart->subtotal();
            $shippingMeta = $this->shipping->cartSummaryMeta($subtotal);
            $coupon = $this->coupons->getApplied();
            $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;

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
                'discount' => $discount,
                'total' => max(0, $subtotal - $discount + $shippingMeta['shipping']),
                'product_id' => $product->id,
                'shipping_enabled' => $shippingMeta['shipping_enabled'],
                'has_free_threshold' => $shippingMeta['has_free_threshold'],
                'show_shipping_progress' => $shippingMeta['show_progress'],
                'amount_to_free' => $shippingMeta['amount_to_free'],
                'free_shipping_progress' => $shippingMeta['free_shipping_progress'],
            ] + $extra, $status);
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
