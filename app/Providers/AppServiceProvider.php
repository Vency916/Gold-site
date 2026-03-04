<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('app_settings')) {
            $siteName = \App\Models\AppSetting::where('key', 'site_name')->first()->value ?? config('app.name');
            View::share('siteName', $siteName);

            // Share Settlement Protocols
            $settlementKeys = [
                'bank_beneficiary', 'bank_iban', 'bank_swift', 'bank_name', 'bank_network', 'bank_transfer_enabled',
                'mailing_name', 'mailing_address_1', 'mailing_address_2', 'mailing_city', 'mailing_postcode', 'cash_mailing_enabled'
            ];
            $settlementSettings = \App\Models\AppSetting::whereIn('key', $settlementKeys)->get()->pluck('value', 'key');
            View::share('settlementSettings', $settlementSettings);

            // Dynamic SMTP Configuration
            try {
                $smtpKeys = ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'];
                $smtp = \App\Models\AppSetting::whereIn('key', $smtpKeys)->get()->pluck('value', 'key');

                if ($smtp->has('mail_host') && !empty($smtp['mail_host'])) {
                    config([
                        'mail.mailers.smtp.host' => $smtp['mail_host'],
                        'mail.mailers.smtp.port' => $smtp['mail_port'] ?? '587',
                        'mail.mailers.smtp.username' => $smtp['mail_username'] ?? null,
                        'mail.mailers.smtp.password' => $smtp['mail_password'] ?? null,
                        'mail.mailers.smtp.encryption' => $smtp['mail_encryption'] ?? 'tls',
                        'mail.from.address' => $smtp['mail_from_address'] ?? config('mail.from.address'),
                        'mail.from.name' => $smtp['mail_from_name'] ?? config('mail.from.name'),
                    ]);
                }
            } catch (\Exception $e) {
                // Fail silently if table doesn't exist yet
            }
        } else {
            View::share('siteName', config('app.name'));
        }
    }
}
