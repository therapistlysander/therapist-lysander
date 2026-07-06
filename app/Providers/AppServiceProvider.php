<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register GoogleCalendarService as singleton
        $this->app->singleton(\App\Services\GoogleCalendarService::class);

        // Override translation loader to support database-driven UI translations
        $this->app->singleton('translation.loader', function ($app) {
            return new \App\Translation\DatabaseTranslationLoader(
                $app['files'],
                $app['path.lang']
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDynamicMail();
        $this->registerBladeDirectives();
        $this->registerDatabaseTranslationLoader();
    }

    /**
     * Force the Translator to use DatabaseTranslationLoader.
     * Must be done in boot() after all providers are registered.
     */
    private function registerDatabaseTranslationLoader(): void
    {
        try {
            $translator = $this->app->make('translator');
            $dbLoader = new \App\Translation\DatabaseTranslationLoader(
                $this->app['files'],
                $this->app['path.lang']
            );
            $translator->setLoader($dbLoader);
        } catch (\Throwable $e) {
            // Silently fail if translator not available
        }
    }

    /**
     * Register custom Blade directives.
     */
    private function registerBladeDirectives(): void
    {
        // @localize('/path') — prepends current locale to local URLs
        Blade::directive('localize', function ($expression) {
            return "<?php echo e(\App\Providers\AppServiceProvider::localizeUrl($expression)); ?>";
        });
    }

    /**
     * Prepend current locale to a local URL path.
     */
    public static function localizeUrl(?string $url): string
    {
        if (empty($url)) return '#';
        // Only prefix local URLs starting with /
        if (!str_starts_with($url, '/') || str_starts_with($url, '//')) return $url;
        $locale = app()->getLocale();
        $supported = config('app.supported_locales', ['en', 'nl']);
        // Don't double-prefix if already has locale
        $segments = explode('/', ltrim($url, '/'));
        if (in_array($segments[0] ?? '', $supported, true)) return $url;
        return '/' . $locale . $url;
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
