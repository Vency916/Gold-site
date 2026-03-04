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

class CheckoutController extends Controller
{
    public function details()
    {
        if (empty(Session::get('cart', []))) {
            return redirect()->route('cart.index')->with('error', 'Your registry is empty.');
        }
        
        $checkoutData = Session::get('checkout_data', []);
        return view('checkout.details', compact('checkoutData'));
    }

    public function postDetails(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_postcode' => 'required|string|max:20',
        ]);

        Session::put('checkout_data', $validated);
        return redirect()->route('checkout.payment');
    }

    public function payment()
    {
        if (!Session::has('checkout_data')) {
            return redirect()->route('checkout.details');
        }
        
        $checkoutData = Session::get('checkout_data');
        $cryptoWallets = \App\Models\CryptoWallet::where('is_active', true)->get();

        return view('checkout.payment', compact('checkoutData', 'cryptoWallets'));
    }

    public function postPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,crypto_transfer,cash_mailing',
            'currency' => 'nullable|required_if:payment_method,bank_transfer',
            'network' => 'nullable|required_if:payment_method,crypto_transfer',
        ]);

        $checkoutData = Session::get('checkout_data');
        $checkoutData['payment_method'] = $request->payment_method;
        $checkoutData['payment_currency'] = $request->currency;
        $checkoutData['payment_network'] = $request->network;
        Session::put('checkout_data', $checkoutData);

        return redirect()->route('checkout.review');
    }

    public function review()
    {
        if (!Session::has('checkout_data') || !isset(Session::get('checkout_data')['payment_method'])) {
            return redirect()->route('checkout.payment');
        }

        $checkoutData = Session::get('checkout_data');
        $cart = Session::get('cart', []);

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $unitPrice = $product->base_price;
                $itemSubtotal = $unitPrice * $details['quantity'];
                
                $subtotal += $itemSubtotal; // Accumulate subtotal for all items
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                ];
            }
        }
        
        $total = $subtotal; // Shipping is complimentary for now
        
        // Calculate Discount
        $discountRate = \App\Models\AppSetting::where('key', 'cash_mailing_discount')->first()->value ?? 0;
        $discountAmount = 0;
        
        if ($checkoutData['payment_method'] === 'cash_mailing' && $discountRate > 0) {
            $discountAmount = $subtotal * ($discountRate / 100);
            $total -= $discountAmount;
        }

        return view('checkout.review', compact('cartItems', 'subtotal', 'total', 'checkoutData', 'discountRate', 'discountAmount'));
    }

    public function complete()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $cart = Session::get('cart', []);
        $checkoutData = Session::get('checkout_data');

        if (empty($cart) || !$checkoutData) {
            return redirect()->route('cart.index');
        }

        $subtotalAmount = 0; // This will be the sum of all item prices before discount
        $totalAmount = 0; // This will be the final amount after discount

        // First, calculate the subtotal for all items
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $unitPrice = $product->base_price;
                $subtotalAmount += $unitPrice * $details['quantity'];
            }
        }

        $totalAmount = $subtotalAmount; // Assuming shipping is complimentary

        // Apply Discount
        $discountRate = \App\Models\AppSetting::where('key', 'cash_mailing_discount')->first()->value ?? 0;
        if ($checkoutData['payment_method'] === 'cash_mailing' && $discountRate > 0) {
            $discountAmount = $subtotalAmount * ($discountRate / 100);
            $totalAmount -= $discountAmount;
        }

        // Determine Payment Address
        $paymentAddress = null;
        if ($checkoutData['payment_method'] === 'crypto_transfer') {
            $wallet = \App\Models\CryptoWallet::where('network', $checkoutData['payment_network'])->where('is_active', true)->first();
            $paymentAddress = $wallet ? $wallet->wallet_address : null;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'shipping_name' => $checkoutData['shipping_name'],
            'shipping_phone' => $checkoutData['shipping_phone'],
            'shipping_address' => $checkoutData['shipping_address'],
            'shipping_city' => $checkoutData['shipping_city'],
            'shipping_postcode' => $checkoutData['shipping_postcode'],
            'payment_method' => $checkoutData['payment_method'],
            'payment_currency' => $checkoutData['payment_currency'] ?? $checkoutData['currency'] ?? null,
            'payment_network' => $checkoutData['payment_network'] ?? $checkoutData['network'] ?? null,
            'payment_address' => $paymentAddress,
            'subtotal' => $subtotalAmount, // Store subtotal before discount
            'total_amount' => $totalAmount, // Store total after discount
            'status' => 'pending_payment',
        ]);

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $unitPrice = $product->base_price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price_at_purchase' => $unitPrice,
                    'metal_price_at_purchase' => 0, // Legacy field: static pricing encapsulates total value
                ]);
                
                // NOTE: Asset fulfillment is now decoupled from checkout.
                // Holdings will be updated upon institutional approval of the payment receipt.
            }
        }

        Session::forget('cart');
        Session::forget('checkout_data');

        return redirect()->route('checkout.payment-details', $order->id)->with('success', 'Acquisition authorized. Please complete the settlement protocol.');
    }

    public function paymentDetails(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.payment-details', compact('order'));
    }

    public function submitReceipt(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_method === 'cash_mailing') {
            $request->validate([
                'tracking_code' => 'required|string|max:255',
            ]);
            
            $order->update([
                'receipt_path' => 'tracking: ' . $request->tracking_code,
                'status' => 'pending_approval'
            ]);
        } else {
            $request->validate([
                'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            ]);

            if ($request->hasFile('receipt')) {
                $path = $request->file('receipt')->store('receipts', 'public');
                $order->update([
                    'receipt_path' => $path,
                    'status' => 'pending_approval'
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Receipt submitted for institutional verification. Assets will be vaulted upon approval.');
    }
}
