<?php

namespace App\Http\Controllers;

use App\Models\SpotPrice;
use App\Models\UserHolding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HoldingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $holdings = UserHolding::with('product')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $goldPrice = SpotPrice::where('metal', 'gold')->latest()->first();
        $silverPrice = SpotPrice::where('metal', 'silver')->latest()->first();

        $holdingsData = $holdings->map(function ($holding) use ($goldPrice, $silverPrice) {
            if (!$holding->product) return null;

            return [
                'name' => $holding->product->name,
                'metal' => $holding->product->metal,
                'quantity' => $holding->quantity,
                'purchase_price' => $holding->purchase_price,
                'current_value' => $holding->quantity * $holding->product->base_price,
                'created_at' => $holding->created_at,
                'image_path' => $holding->product->image_path,
            ];
        })->filter()->filter(function ($item) {
            return strpos($item['name'], 'Institutional Digital') === false;
        });

        return view('holdings.index', [
            'holdings' => $holdingsData,
        ]);
    }
}
