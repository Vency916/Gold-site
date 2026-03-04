<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SpotPrice;
use App\Models\UserHolding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'metal' => 'required|in:gold,silver',
            'type' => 'required|in:buy,sell',
            'grams' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Authentication required.'], 401);
        }

        $metal = $request->metal;
        $type = $request->type;
        $grams = $request->grams;

        // Find the digital product
        $product = Product::where('name', 'LIKE', "Institutional Digital " . ucfirst($metal))->first();
        if (!$product) {
            \Log::error("Digital asset class not found for metal: {$metal}");
            return response()->json(['success' => false, 'message' => 'Digital asset class not found.'], 404);
        }

        // Centralized Valuation Sync
        $unitPrice = $product->current_price;
        $totalPriceBase = $unitPrice * $grams;
        $totalPriceNative = \App\Services\CurrencyService::convert($totalPriceBase);

        return DB::transaction(function () use ($user, $product, $type, $grams, $totalPriceNative, $unitPrice) {
            
            if ($type === 'buy') {
                if ($user->balance < $totalPriceNative) {
                    return response()->json(['success' => false, 'message' => 'Insufficient liquid capital for this acquisition.'], 400);
                }

                // Update balance
                $user->balance -= $totalPriceNative;
                $user->save();

                // Update or create holding
                $holding = UserHolding::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();

                if ($holding) {
                    $holding->quantity += $grams;
                    $holding->save();
                } else {
                    UserHolding::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'quantity' => $grams,
                        'purchase_price' => $unitPrice,
                    ]);
                }

                return response()->json([
                    'success' => true, 
                    'message' => "Successfully acquired " . number_format($grams, 2) . "g of Digital " . ucfirst($product->metal),
                    'new_balance' => number_format($user->balance, 2),
                    'redirect_url' => route('vault.index')
                ]);

            } else {
                // Sell logic
                $holding = UserHolding::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (!$holding || $holding->quantity < $grams) {
                    return response()->json(['success' => false, 'message' => 'Insufficient vaulted assets for this liquidation.'], 400);
                }

                // Update holding
                $holding->quantity -= $grams;
                if ($holding->quantity <= 0) {
                    $holding->delete();
                } else {
                    $holding->save();
                }

                // Update balance
                $user->balance += $totalPriceNative;
                $user->save();

                return response()->json([
                    'success' => true, 
                    'message' => "Successfully liquidated " . number_format($grams, 2) . "g of Digital " . ucfirst($product->metal),
                    'new_balance' => number_format($user->balance, 2)
                ]);
            }
        });
    }
}
