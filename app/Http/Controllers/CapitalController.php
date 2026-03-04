<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Deposit;

class CapitalController extends Controller
{
    public function index()
    {
        return view('capital.index', [
            'user' => Auth::user(),
        ]);
    }

    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        Session::put('funding_amount', $request->amount);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('capital.fund.payment'),
                'message' => "Funding request for " . number_format($request->amount, 2) . " initiated."
            ]);
        }

        return redirect()->route('capital.fund.payment')->with('success', "Funding protocol initiated.");
    }

    public function payment()
    {
        if (!Session::has('funding_amount')) {
            return redirect()->route('capital.index');
        }
        $cryptoWallets = \App\Models\CryptoWallet::where('is_active', true)->get();
        return view('capital.payment', compact('cryptoWallets'));
    }

    public function postPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,crypto_transfer',
            'currency' => 'nullable|required_if:payment_method,bank_transfer',
            'network' => 'nullable|required_if:payment_method,crypto_transfer',
        ]);

        $paymentAddress = null;
        if ($request->payment_method === 'crypto_transfer') {
            $wallet = \App\Models\CryptoWallet::where('network', $request->network)->where('is_active', true)->first();
            $paymentAddress = $wallet ? $wallet->wallet_address : null;
        }

        $fundingData = [
            'amount' => Session::get('funding_amount'),
            'payment_method' => $request->payment_method,
            'payment_currency' => $request->currency,
            'payment_network' => $request->network,
            'payment_address' => $paymentAddress,
        ];

        Session::put('funding_data', $fundingData);

        return redirect()->route('capital.fund.review');
    }

    public function review()
    {
        if (!Session::has('funding_data')) {
            return redirect()->route('capital.fund.payment');
        }

        $fundingData = Session::get('funding_data');
        return view('capital.review', compact('fundingData'));
    }

    public function complete(Request $request)
    {
        if (!Session::has('funding_data')) {
            return redirect()->route('capital.index');
        }

        return redirect()->route('capital.fund.instructions')->with('success', 'Funding request authorized. Please complete the settlement protocol.');
    }

    public function instructions()
    {
        if (!Session::has('funding_data')) {
            return redirect()->route('capital.index');
        }

        $fundingData = Session::get('funding_data');
        return view('capital.payment-details', compact('fundingData'));
    }

    public function submitReceipt(Request $request)
    {
        if (!Session::has('funding_data')) {
            return redirect()->route('capital.index');
        }

        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $fundingData = Session::get('funding_data');

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            
            // Lookup wallet address if crypto
            $paymentAddress = null;
            if ($fundingData['payment_method'] === 'crypto_transfer') {
                $wallet = \App\Models\CryptoWallet::where('network', $fundingData['payment_network'])->where('is_active', true)->first();
                $paymentAddress = $wallet ? $wallet->wallet_address : null;
            }

            Deposit::create([
                'user_id' => Auth::id(),
                'amount' => $fundingData['amount'],
                'payment_method' => $fundingData['payment_method'],
                'payment_currency' => $fundingData['payment_currency'] ?? null,
                'payment_network' => $fundingData['payment_network'] ?? null,
                'payment_address' => $paymentAddress,
                'status' => 'pending_approval',
                'receipt_path' => $path,
            ]);
        }

        Session::forget(['funding_amount', 'funding_data']);

        return redirect()->route('dashboard')->with('success', 'Deposit submitted and registered for institutional verification.');
    }
}
