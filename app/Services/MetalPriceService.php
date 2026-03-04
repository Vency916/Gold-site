<?php

namespace App\Services;

use App\Models\SpotPrice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetalPriceService
{
    /**
     * Fetch the latest metal prices from the API.
     */
    public function fetchLatestPrices()
    {
        $apiKey = \App\Models\AppSetting::where('key', 'metal_api_key')->first()->value ?? null;
        $conversionRate = \App\Models\AppSetting::where('key', 'metal_conversion_rate')->first()->value ?? 0.78;

        if (!$apiKey) {
            Log::warning('Metal Price API Key is missing. Skipping update.');
            return;
        }

        $metals = ['XAU' => 'gold', 'XAG' => 'silver'];
        $baseUrl = 'https://www.goldapi.io/api';

        foreach ($metals as $symbol => $slug) {
            try {
                $response = Http::withHeaders([
                    'x-access-token' => $apiKey,
                    'Content-Type' => 'application/json'
                ])->get("{$baseUrl}/{$symbol}/USD");

                if ($response->successful()) {
                    $data = $response->json();
                    $priceInUSD = $data['price'];

                    SpotPrice::create([
                        'metal' => $slug,
                        'price' => $priceInUSD,
                        'currency' => 'USD',
                    ]);

                    Log::info("{$slug} price updated successfully (Raw USD): {$priceInUSD}");
                } else {
                    Log::error("Failed to fetch {$slug} price from API. Status: " . $response->status());
                }
            } catch (\Exception $e) {
                Log::error("Exception occurred while updating {$slug} price: " . $e->getMessage());
            }
        }
    }

    /**
     * Get the current spot price for a specific metal.
     */
    public function getCurrentPrice($metal)
    {
        return SpotPrice::where('metal', $metal)
            ->latest()
            ->first();
    }
}
