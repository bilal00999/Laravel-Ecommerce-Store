<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Show checkout form
     */
    public function show(Request $request)
    {
        // Get user's cart
        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id()],
            ['user_id' => auth()->id()]
        );

        // Get cart items
        $items = $cart->items()->with('product')->get();

        if ($items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Add items before checkout.');
        }

        // Calculate totals
        $subtotal = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $tax = $subtotal * 0.08;
        $total = $subtotal + $tax;

        // Get user's address from profile if available
        $user = auth()->user();

        return view('checkout.show', [
            'cart' => $cart,
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'user' => $user,
        ]);
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        // Get user's cart
        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Cart not found.');
        }

        // Get cart items
        $items = $cart->items()->with('product')->get();

        if ($items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate request
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            'terms' => 'required|accepted',
        ]);

        // Calculate totals
        $subtotal = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $tax = $subtotal * 0.08;
        $total = $subtotal + $tax;

        try {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => json_encode([
                    'full_name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'street_address' => $validated['street_address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'postal_code' => $validated['postal_code'],
                    'country' => $validated['country'],
                ]),
                'notes' => $request->input('notes', null),
            ]);

            // Create order items from cart items
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'total' => $item->product->price * $item->quantity,
                ]);
            }

            // Clear cart
            $cart->items()->delete();

            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    /**
     * Show order success page
     */
    public function success(Order $order)
    {
        // Ensure user can only see their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $items = $order->items()->with('product')->get();

        return view('checkout.success', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    /**
     * Show user's order history
     */
    public function orders(Request $request)
    {
        $orders = auth()->user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('checkout.orders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show single order details
     */
    public function orderDetails(Order $order)
    {
        // Ensure user can only see their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $items = $order->items()->with('product')->get();
        $shippingAddress = json_decode($order->shipping_address, true);

        return view('checkout.order-details', [
            'order' => $order,
            'items' => $items,
            'shippingAddress' => $shippingAddress,
        ]);
    }
}
