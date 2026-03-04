<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SpotPrice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserHolding;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $goldPrice = SpotPrice::where('metal', 'gold')->latest()->first();
        $silverPrice = SpotPrice::where('metal', 'silver')->latest()->first();

        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $unitPrice = $product->base_price;
                $subtotal = $unitPrice * $details['quantity'];
                
                $total += $subtotal;
                $cartItems[] = [
                    'id' => $id,
                    'name' => $product->name,
                    'metal' => $product->metal,
                    'weight' => $product->weight,
                    'quantity' => $details['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'image_path' => $product->image_path,
                ];
            }
        }

        return view('cart', compact('cartItems', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Institutional Protection: Prevent digital proxies from entering the retail cart
        if (strpos($product->name, 'Institutional Digital') !== false) {
            return redirect()->back()->with('error', 'Institutional digital assets must be managed via the Asset Exchange protocol.');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "quantity" => 1
            ];
        }

        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = Session::get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            Session::put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = Session::get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                Session::put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Product removed from cart!');
        }
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please sign in to complete your bullion purchase.');
        }

        $user = Auth::user();
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('welcome')->with('error', 'Your cart is empty.');
        }

        $goldPrice = SpotPrice::where('metal', 'gold')->latest()->first();
        $silverPrice = SpotPrice::where('metal', 'silver')->latest()->first();

        // Start Order
        $total = 0;
        $order = Order::create([
            'user_id' => $user->id,
            'subtotal' => 0, // Will update after loop
            'total_amount' => 0,
            'status' => 'completed',
        ]);

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $unitPrice = $product->base_price;
                $subtotal = $unitPrice * $details['quantity'];
                
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price_at_purchase' => $unitPrice,
                    'metal_price_at_purchase' => $spotPriceValue,
                ]);

                // Update User Holdings (Vault)
                $existingHolding = UserHolding::where('user_id', $user->id)
                    ->where('product_id', $id)
                    ->first();

                if ($existingHolding) {
                    // Update average purchase price logic simplified: weighted average
                    $totalQty = $existingHolding->quantity + $details['quantity'];
                    $newAvgPrice = (($existingHolding->quantity * $existingHolding->purchase_price) + ($details['quantity'] * $unitPrice)) / $totalQty;
                    
                    $existingHolding->update([
                        'quantity' => $totalQty,
                        'purchase_price' => $newAvgPrice
                    ]);
                } else {
                    UserHolding::create([
                        'user_id' => $user->id,
                        'product_id' => $id,
                        'quantity' => $details['quantity'],
                        'purchase_price' => $unitPrice
                    ]);
                }
            }
        }

        $order->update([
            'subtotal' => $total,
            'total_amount' => $total
        ]);

        // Clear Cart
        Session::forget('cart');

        return redirect()->route('dashboard')->with('success', 'Checkout successful! Your bullion has been added to your vault.');
    }
}
