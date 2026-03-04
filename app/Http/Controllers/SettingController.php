<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\CryptoWallet;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Add default setting if it doesn't exist yet
        $cashMailingDiscount = AppSetting::firstOrCreate(
            ['key' => 'cash_mailing_discount'],
            [
                'value' => '0',
                'type' => 'float',
                'description' => 'Percentage discount applied to the subtotal when the Cash Mailing payment method is selected.'
            ]
        );

        $siteName = AppSetting::firstOrCreate(
            ['key' => 'site_name'],
            ['value' => config('app.name'), 'type' => 'string', 'description' => 'Site display name']
        );

        $smtpKeys = ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'];
        $smtpSettings = AppSetting::whereIn('key', $smtpKeys)->get()->keyBy('key');

        $metalPriceKeys = ['metal_api_key', 'metal_conversion_rate'];
        $metalSettings = AppSetting::whereIn('key', $metalPriceKeys)->get()->keyBy('key');

        $settings = compact('cashMailingDiscount', 'siteName', 'smtpSettings', 'metalSettings');
        $cryptoWallets = CryptoWallet::all();
        return view('admin.settings', compact('settings', 'cryptoWallets'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'cash_mailing_discount' => 'required|numeric|min:0|max:100',
            'site_name' => 'required|string|max:255',
        ]);

        AppSetting::updateOrCreate(
            ['key' => 'cash_mailing_discount'],
            ['value' => $request->cash_mailing_discount]
        );

        AppSetting::updateOrCreate(['key' => 'site_name'], ['value' => $request->site_name]);

        $settingFields = ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name', 'metal_api_key', 'metal_conversion_rate'];
        foreach ($settingFields as $field) {
            if ($request->has($field)) {
                AppSetting::updateOrCreate(['key' => $field], ['value' => $request->$field ?? '']);
            }
        }

        return back()->with('success', 'Global configuration protocols updated successfully.');
    }
    public function storeCryptoWallet(Request $request)
    {
        $request->validate([
            'coin_name' => 'required|string|max:255',
            'symbol' => 'required|string|max:20',
            'network' => 'required|string|max:255',
            'wallet_address' => 'required|string|max:255',
        ]);

        CryptoWallet::create($request->all());

        return back()->with('success', 'New crypto node established successfully.');
    }

    public function updateCryptoWallet(Request $request, CryptoWallet $wallet)
    {
        $request->validate([
            'coin_name' => 'required|string|max:255',
            'symbol' => 'required|string|max:20',
            'network' => 'required|string|max:255',
            'wallet_address' => 'required|string|max:255',
        ]);

        $wallet->update($request->all());

        return back()->with('success', 'Crypto node configuration updated.');
    }

    public function destroyCryptoWallet(CryptoWallet $wallet)
    {
        $wallet->delete();
        return back()->with('success', 'Crypto node decommissioned.');
    }

    public function paymentMethods()
    {
        $bankKeys = ['bank_beneficiary', 'bank_iban', 'bank_swift', 'bank_name', 'bank_network', 'bank_transfer_enabled'];
        $mailingKeys = ['mailing_name', 'mailing_address_1', 'mailing_address_2', 'mailing_city', 'mailing_postcode', 'cash_mailing_enabled'];
        
        $bankSettings = AppSetting::whereIn('key', $bankKeys)->get()->keyBy('key');
        $mailingSettings = AppSetting::whereIn('key', $mailingKeys)->get()->keyBy('key');

        return view('admin.payment-methods', compact('bankSettings', 'mailingSettings'));
    }

    public function updatePaymentMethods(Request $request)
    {
        $keys = $request->except('_token');
        
        foreach ($keys as $key => $value) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return back()->with('success', 'Institutional settlement protocols updated.');
    }
}
