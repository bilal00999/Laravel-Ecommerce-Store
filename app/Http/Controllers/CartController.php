<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get or create cart for current user/session
     */
    protected function getCart(Request $request)
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                ['user_id' => auth()->id()]
            );
        } else {
            // For guest users, use session ID
            $sessionId = $request->session()->getId();
            return Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['session_id' => $sessionId]
            );
        }
    }

    /**
     * Display the shopping cart
     */
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $items = $cart->items()->with('product')->get();

        $total = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', [
            'cart' => $cart,
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $cart = $this->getCart($request);
        $quantity = $request->input('quantity', 1);

        // Check if product already in cart
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            // Increase quantity
            $item->update([
                'quantity' => $item->quantity + $quantity
            ]);
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', $product->name . ' added to cart!');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        // Get the cart item
        $item = CartItem::find($id);
        
        if (!$item) {
            return back()->with('error', 'Cart item not found.');
        }

        // Verify the cart item belongs to current user's cart
        $cart = $this->getCart($request);
        if ($item->cart_id != $cart->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $quantity = $request->input('quantity', 1);

        if ($quantity > 0) {
            $item->update(['quantity' => $quantity]);
            return back()->with('success', 'Cart updated!');
        } else {
            return $this->remove($request, $id);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $id)
    {
        // Get the cart item
        $item = CartItem::find($id);
        
        if (!$item) {
            return back()->with('error', 'Cart item not found.');
        }

        // Verify the cart item belongs to current user's cart
        $cart = $this->getCart($request);
        if ($item->cart_id != $cart->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $productName = $item->product->name;
        $item->delete();

        return back()->with('success', $productName . ' removed from cart!');
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request)
    {
        $cart = $this->getCart($request);
        CartItem::where('cart_id', $cart->id)->delete();

        return back()->with('success', 'Cart cleared!');
    }

    /**
     * Get cart count for header
     */
    public function getCount(Request $request)
    {
        $cart = $this->getCart($request);
        $count = $cart->items()->sum('quantity');

        return response()->json(['count' => $count]);
    }
}
