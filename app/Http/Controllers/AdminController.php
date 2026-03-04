<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\UserHolding;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalBalances = User::sum('balance');
        $pendingOrders = Order::where('status', 'pending_approval')->count();
        $recentUsers = User::latest()->take(10)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalBalances', 'pendingOrders', 'recentUsers'));
    }

    public function orders()
    {
        $orders = Order::with('user', 'items.product')
            ->whereIn('status', ['pending_approval', 'approved', 'rejected'])
            ->latest()
            ->get();

        return view('admin.orders', compact('orders'));
    }

    public function approveOrder(Order $order)
    {
        if ($order->status !== 'pending_approval') {
            return back()->with('error', 'Order is not in a pending state.');
        }

        foreach ($order->items as $item) {
            $holding = UserHolding::where('user_id', $order->user_id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($holding) {
                $totalQty = $holding->quantity + $item->quantity;
                $newAvgPrice = (($holding->quantity * $holding->purchase_price) + ($item->quantity * $item->price_at_purchase)) / $totalQty;
                
                $holding->update([
                    'quantity' => $totalQty,
                    'purchase_price' => $newAvgPrice
                ]);
            } else {
                UserHolding::create([
                    'user_id' => $order->user_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'purchase_price' => $item->price_at_purchase
                ]);
            }
        }

        $order->update(['status' => 'approved']);

        return back()->with('success', "Order #{$order->id} has been approved and assets vaulted.");
    }

    public function rejectOrder(Order $order)
    {
        $order->update(['status' => 'rejected']);
        return back()->with('success', "Order #{$order->id} has been rejected.");
    }

    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function updateUserBalance(Request $request, User $user)
    {
        $request->validate([
            'balance' => 'required|numeric|min:0'
        ]);

        $user->update(['balance' => $request->balance]);

        return back()->with('success', "User {$user->email} balance updated.");
    }

    public function products()
    {
        $products = Product::latest()->get();
        return view('admin.products', compact('products'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'metal' => 'required|in:gold,silver',
            'category' => 'required|in:coin,bar',
            'weight' => 'required|numeric|min:0',
            'weight_unit' => 'required|string|max:10',
            'base_price' => 'required|numeric|min:0',
            'purity' => 'required|numeric|min:0|max:1',
            'premium_percentage' => 'required|numeric|min:0',
            'premium_fixed' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create(collect($validated)->except('image')->toArray());

        return redirect()->route('admin.products')->with('success', "Institutional asset '{$request->name}' registered successfully.");
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'metal' => 'required|in:gold,silver',
            'category' => 'required|in:coin,bar',
            'weight' => 'required|numeric|min:0',
            'weight_unit' => 'required|string|max:10',
            'base_price' => 'required|numeric|min:0',
            'purity' => 'required|numeric|min:0|max:1',
            'premium_percentage' => 'required|numeric|min:0',
            'premium_fixed' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update(collect($validated)->except('image')->toArray());

        return redirect()->route('admin.products')->with('success', "Asset '{$product->name}' orchestration updated.");
    }

    public function destroyProduct(Product $product)
    {
        $name = $product->name;

        // Purge visual protocol if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', "Institutional asset '{$name}' has been decommissioned and purged from the registry.");
    }

    public function deposits()
    {
        $deposits = \App\Models\Deposit::with('user')
            ->whereIn('status', ['pending_approval', 'approved', 'rejected'])
            ->latest()
            ->get();

        return view('admin.deposits', compact('deposits'));
    }

    public function approveDeposit(\App\Models\Deposit $deposit)
    {
        if ($deposit->status !== 'pending_approval') {
            return back()->with('error', 'Deposit is not in a pending state.');
        }

        $user = $deposit->user;
        $user->balance += $deposit->amount;
        $user->save();

        $deposit->update(['status' => 'approved']);

        return back()->with('success', "Deposit #{$deposit->id} has been approved. Capital of " . number_format($deposit->amount, 2) . " has been allocated to {$user->email}.");
    }

    public function rejectDeposit(\App\Models\Deposit $deposit)
    {
        $deposit->update(['status' => 'rejected']);
        return back()->with('success', "Deposit #{$deposit->id} has been rejected.");
    }
}
