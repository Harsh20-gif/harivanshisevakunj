<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $deliveryCharge = empty($cart) ? 0 : 100;
        $grandTotal = $subtotal + $deliveryCharge;

        // Generate combined WhatsApp inquiry for the entire cart
        $cleanNumber = \App\Support\WhatsApp::getBusinessNumber();
        $whatsappUrl = null;

        if ($cleanNumber && !empty($cart)) {
            $msg = "Radhe Radhe! I would like to order these sacred seva items from Harivanshi Seva Kunj:\n\n";
            $i = 1;
            foreach ($cart as $item) {
                $msg .= $i++ . ". " . $item['title'] . " (Qty: " . $item['quantity'] . ") - ₹" . number_format($item['price'] * $item['quantity']) . "\n";
            }
            $msg .= "\n--------------------------------";
            $msg .= "\n• Items Subtotal: ₹" . number_format($subtotal);
            $msg .= "\n• Delivery Charge: ₹" . number_format($deliveryCharge);
            $msg .= "\n• Total Payable Amount: ₹" . number_format($grandTotal);
            $msg .= "\n--------------------------------";
            $msg .= "\n\nPlease confirm availability and dispatch details. Order via WhatsApp!";
            $whatsappUrl = "https://wa.me/" . $cleanNumber . "?text=" . rawurlencode($msg);
        }

        return view('cart.index', compact('cart', 'totalItems', 'subtotal', 'deliveryCharge', 'grandTotal', 'whatsappUrl'));
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = isset($validated['quantity']) ? (int) $validated['quantity'] : 1;

        // Ensure product is active and published
        $product = Product::published()->findOrFail($productId);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id'       => $product->id,
                'title'    => $product->title,
                'price'    => (float) $product->price,
                'quantity' => $quantity,
                'image'    => $product->image_url,
                'slug'     => $product->slug,
            ];
        }

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => '“' . $product->title . '” added to your cart!',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
            ]);
        }

        return redirect()->back()->with('success', '“' . $product->title . '” added to your cart!');
    }

    /**
     * Update item quantity in the cart.
     */
    public function update(Request $request, int $productId): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = (int) $validated['quantity'];
            session()->put('cart', $cart);
        }

        $cartCount = array_sum(array_column($cart, 'quantity'));
        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $deliveryCharge = empty($cart) ? 0 : 100;
        $grandTotal = $subtotal + $deliveryCharge;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'         => true,
                'cart_count'      => $cartCount,
                'subtotal'        => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'grand_total'     => $grandTotal,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(Request $request, int $productId): JsonResponse|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $cartCount = array_sum(array_column($cart, 'quantity'));
        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $deliveryCharge = empty($cart) ? 0 : 100;
        $grandTotal = $subtotal + $deliveryCharge;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'         => true,
                'message'         => 'Item removed from cart.',
                'cart_count'      => $cartCount,
                'subtotal'        => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'grand_total'     => $grandTotal,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
