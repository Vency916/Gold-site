<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SpotPrice;
use App\Models\UserHolding;
use App\Models\VaultSaving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VaultController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Flexible holdings (Digital only)
        $flexibleHoldings = UserHolding::with('product')
            ->where('user_id', $user->id)
            ->whereHas('product', function($query) {
                $query->where('name', 'LIKE', '%Institutional Digital%');
            })
            ->get();

        // Vault savings
        $vaultSavings = VaultSaving::with('product')
            ->where('user_id', $user->id)
            ->get();

        // Latest spot prices
        $goldPrice = SpotPrice::where('metal', 'gold')->latest()->first();
        $silverPrice = SpotPrice::where('metal', 'silver')->latest()->first();
        
        $ozPerGram = 1 / 31.1034768;
        $goldGramPrice = ($goldPrice->price ?? 0) * $ozPerGram;
        $silverGramPrice = ($silverPrice->price ?? 0) * $ozPerGram;

        // Calculate flexible data
        $flexibleData = $flexibleHoldings->map(function ($holding) {
            $currentValue = $holding->quantity * $holding->product->current_price;
            $purchaseCost = $holding->quantity * $holding->purchase_price;

            return [
                'id' => $holding->id,
                'name' => $holding->product->name,
                'metal' => $holding->product->metal,
                'quantity' => $holding->quantity,
                'current_value' => $currentValue,
                'profit_loss' => $currentValue - $purchaseCost,
            ];
        });

        // Calculate vault data
        $savingsData = $vaultSavings->map(function ($saving) {
            $currentValue = $saving->quantity * $saving->product->current_price;
            
            // Monthly yield projection: (Quantity * Spot) * (Yield / 12)
            $monthlyYield = $currentValue * ($saving->annual_yield_rate / 100 / 12);

            return [
                'id' => $saving->id,
                'name' => $saving->product->name,
                'metal' => $saving->product->metal,
                'quantity' => $saving->quantity,
                'current_value' => $currentValue,
                'yield_rate' => $saving->annual_yield_rate,
                'monthly_yield' => $monthlyYield,
                'started_at' => $saving->started_at,
            ];
        });

        return view('vault.index', [
            'user' => $user,
            'flexibleHoldings' => $flexibleData,
            'vaultSavings' => $savingsData,
            'totalFlexibleValue' => $flexibleData->sum('current_value'),
            'totalVaultValue' => $savingsData->sum('current_value'),
            'totalMonthlyYield' => $savingsData->sum('monthly_yield'),
            'goldGramPrice' => $goldGramPrice,
            'silverGramPrice' => $silverGramPrice,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'holding_id' => 'required|exists:user_holdings,id',
            'grams' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $holding = UserHolding::where('id', $request->holding_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($holding->quantity < $request->grams) {
            return response()->json(['success' => false, 'message' => 'Insufficient flexible assets.'], 400);
        }

        return DB::transaction(function () use ($user, $holding, $request) {
            // Deduct from flexible holdings
            $holding->quantity -= $request->grams;
            if ($holding->quantity <= 0) {
                $holding->delete();
            } else {
                $holding->save();
            }

            // Define yield rates
            $yieldRate = $holding->product->metal === 'gold' ? 4.50 : 5.50;

            // Add to vault savings
            $saving = VaultSaving::where('user_id', $user->id)
                ->where('product_id', $holding->product_id)
                ->first();

            if ($saving) {
                $saving->quantity += $request->grams;
                $saving->save();
            } else {
                VaultSaving::create([
                    'user_id' => $user->id,
                    'product_id' => $holding->product_id,
                    'quantity' => $request->grams,
                    'annual_yield_rate' => $yieldRate,
                    'started_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully vaulted " . number_format($request->grams, 2) . "g of Digital " . ucfirst($holding->product->metal) . " for monthly yield."
            ]);
        });
    }
}
