<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class CurrencyService
{
    /**
     * Get the current user's currency preference.
     */
    public static function getUserCurrency(): string
    {
        return Auth::check() ? Auth::user()->currency : 'USD';
    }

    /**
     * Get the currency symbol.
     */
    public static function getSymbol(): string
    {
        return self::getUserCurrency() === 'USD' ? '$' : '£';
    }

    /**
     * Convert an amount between currencies.
     * Option C: Direct Local Pricing (1:1 Absolute Ratio)
     */
    public static function convert($amount): float
    {
        if (self::getUserCurrency() === 'USD') {
            return (float) $amount;
        }

        $rate = \App\Models\AppSetting::where('key', 'metal_conversion_rate')->first()->value ?? 0.78;
        return (float) $amount * (float) $rate;
    }

    /**
     * Format an amount with the correct symbol, assuming it is already in the user's currency.
     */
    public static function formatNative($amount): string
    {
        $symbol = self::getSymbol();
        return $symbol . number_format($amount, 2);
    }

    /**
     * Format an amount with the correct symbol, converting from GBP base if necessary.
     */
    public static function format($amount): string
    {
        $converted = self::convert($amount);
        return self::formatNative($converted);
    }
}
