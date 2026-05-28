<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        $this->configureDynamicMail();
    }

    /**
     * Override mail configuration from database settings.
     */
    private function configureDynamicMail(): void
    {
        try {
            if (!Schema::hasTable('site_settings')) {
                return;
            }

            $emailSettings = \App\Models\SiteSetting::where('group', 'email')
                ->pluck('value', 'key')
                ->toArray();

            if (empty($emailSettings)) {
                return;
            }

            // Set from address/name
            if (!empty($emailSettings['mail_from_address'])) {
                config(['mail.from.address' => $emailSettings['mail_from_address']]);
            }
            if (!empty($emailSettings['mail_from_name'])) {
                config(['mail.from.name' => $emailSettings['mail_from_name']]);
            }

            // If driver is smtp and host is configured, override smtp settings
            $driver = $emailSettings['mail_driver'] ?? 'log';
            if ($driver === 'smtp' && !empty($emailSettings['smtp_host'])) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => $emailSettings['smtp_host'],
                    'mail.mailers.smtp.port' => (int) ($emailSettings['smtp_port'] ?? 587),
                    'mail.mailers.smtp.username' => $emailSettings['smtp_username'] ?? null,
                    'mail.mailers.smtp.password' => $emailSettings['smtp_password'] ?? null,
                    'mail.mailers.smtp.encryption' => $emailSettings['smtp_encryption'] ?? 'tls',
                ]);
            } elseif ($driver === 'log') {
                config(['mail.default' => 'log']);
            }
        } catch (\Throwable $e) {
            // Silently fail during migrations or if DB is unavailable
        }
    }
}
