<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        return view('cart.index', [
            'items' => $this->cart->all(),
            'subtotal' => $this->cart->subtotal(),
            'shipping' => $this->cart->shippingFee(),
            'total' => $this->cart->total(),
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
            return back()->with('error', 'Sorry, this product is out of stock.');
        }

        $this->cart->add($product->id, $qty);

        return back()->with('success', 'Added to cart successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        if ($data['quantity'] > 0 && ! $product->isInStock($data['quantity'])) {
            return back()->with('error', 'Not enough stock available.');
        }

        $this->cart->update($product->id, $data['quantity']);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product->id);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
