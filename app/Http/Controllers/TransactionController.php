<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch Deposits (Capital Injections)
        $deposits = Deposit::where('user_id', $user->id)
            ->whereIn('status', ['pending_approval', 'approved', 'rejected'])
            ->get()
            ->map(function ($deposit) {
                return [
                    'type' => 'Deposit',
                    'date' => $deposit->created_at,
                    'reference' => 'INJ-' . str_pad($deposit->id, 5, '0', STR_PAD_LEFT),
                    'amount' => $deposit->amount,
                    'status' => $deposit->status,
                    'method' => str_replace('_', ' ', $deposit->payment_method),
                    'meta' => $deposit->payment_currency ?? $deposit->payment_network,
                    'is_revenue' => true,
                ];
            });

        // Fetch Orders (Asset Acquisitions)
        $orders = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending_payment', 'pending_approval', 'approved', 'rejected'])
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'Purchase',
                    'date' => $order->created_at,
                    'reference' => 'ACQ-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    'amount' => $order->total_amount,
                    'status' => $order->status,
                    'method' => 'Vault Clearance',
                    'meta' => $order->items->count() . ' Assets',
                    'is_revenue' => false,
                ];
            });

        // Unified & Sorted Collection
        $transactions = $deposits->concat($orders)->sortByDesc('date');

        return view('transactions.index', compact('transactions'));
    }
}
