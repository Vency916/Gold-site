<?php

namespace App\Http\Controllers;

use App\Models\SpotPrice;
use App\Models\UserHolding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user holdings with product details
        $holdings = UserHolding::with('product')
            ->where('user_id', $user->id)
            ->get();

        // Get latest spot prices
        $goldPrice = SpotPrice::where('metal', 'gold')->latest()->first();
        $silverPrice = SpotPrice::where('metal', 'silver')->latest()->first();

        // Initialize valuation accumulators
        $totalGoldValue = 0;
        $totalSilverValue = 0;
        $totalValue = 0; // Exclude liquid capital per user request
        $totalPurchaseCost = 0;
        
        $allHoldingsData = $holdings->map(function ($holding) use ($goldPrice, $silverPrice, &$totalValue, &$totalPurchaseCost, &$totalGoldValue, &$totalSilverValue) {
            if (!$holding->product) {
                return null;
            }

            // Centralized Valuation Sync (Physical uses static base_price, Digital uses Oracle)
            $isDigital = strpos($holding->product->name, 'Institutional Digital') !== false;
            $holdingCurrentValue = $holding->quantity * $holding->product->current_price;
            $holdingPurchaseCost = $holding->quantity * $holding->purchase_price;

            if (!$isDigital) {
                $totalValue += $holdingCurrentValue;
                $totalPurchaseCost += $holdingPurchaseCost;

                if ($holding->product->metal === 'gold') {
                    $totalGoldValue += $holdingCurrentValue;
                } else {
                    $totalSilverValue += $holdingCurrentValue;
                }
            }

            return [
                'name' => $holding->product->name,
                'metal' => $holding->product->metal,
                'quantity' => $holding->quantity,
                'purchase_price' => $holding->purchase_price,
                'current_value' => $holdingCurrentValue,
                'profit_loss' => $holdingCurrentValue - $holdingPurchaseCost,
                'profit_loss_percentage' => $holdingPurchaseCost > 0 ? (($holdingCurrentValue - $holdingPurchaseCost) / $holdingPurchaseCost) * 100 : 0,
                'image_path' => $holding->product->image_path,
                'is_digital' => $isDigital,
            ];
        })->filter();

        // Filter out digital assets and truncate to latest 2 for the dashboard
        $physicalHoldings = $allHoldingsData->filter(function ($item) {
            return strpos($item['name'], 'Institutional Digital') === false;
        })->take(2);

        // Centralized Valuation Sync (Use Product current_price for Alipine.js state)
        $goldProduct = \App\Models\Product::where('name', 'LIKE', '%Institutional Digital Gold%')->first();
        $silverProduct = \App\Models\Product::where('name', 'LIKE', '%Institutional Digital Silver%')->first();

        $goldGramPrice = $goldProduct ? $goldProduct->current_price : 0;
        $silverGramPrice = $silverProduct ? $silverProduct->current_price : 0;

        // Fetch pending acquisitions
        $pendingOrders = \App\Models\Order::where('user_id', $user->id)
            ->whereIn('status', ['pending_payment', 'pending_approval'])
            ->latest()
            ->get();

        // Fetch pending capital injections
        $pendingDeposits = \App\Models\Deposit::where('user_id', $user->id)
            ->where('status', 'pending_approval')
            ->latest()
            ->get();

        // Calculate distribution percentages against asset valuation
        $totalAccountValue = $totalValue + $user->balance;
        $goldPercent = $totalValue > 0 ? ($totalGoldValue / $totalValue) * 100 : 0;
        $silverPercent = $totalValue > 0 ? ($totalSilverValue / $totalValue) * 100 : 0;
        $cashPercent = $totalAccountValue > 0 ? ($user->balance / $totalAccountValue) * 100 : 0;

        return view('dashboard', [
            'user' => $user,
            'holdings' => $physicalHoldings,
            'totalValue' => $totalValue, // Assets only per user request
            'totalGoldValue' => $totalGoldValue,
            'totalSilverValue' => $totalSilverValue,
            'goldPercent' => $goldPercent,
            'silverPercent' => $silverPercent,
            'cashPercent' => $cashPercent,
            'totalAccountValue' => $totalAccountValue,
            'totalProfitLoss' => $totalValue - $totalPurchaseCost,
            'goldPrice' => $goldPrice,
            'silverPrice' => $silverPrice,
            'goldGramPrice' => $goldGramPrice,
            'silverGramPrice' => $silverGramPrice,
            'pendingOrders' => $pendingOrders,
            'pendingDeposits' => $pendingDeposits,
        ]);
    }
}
