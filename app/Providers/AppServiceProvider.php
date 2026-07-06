<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public static bool $bootRan = false;
    public static ?string $injectError = null;
    public static array $injectDebug = [];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register GoogleCalendarService as singleton
        $this->app->singleton(\App\Services\GoogleCalendarService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        self::$bootRan = true;

        // Load DB translation overrides directly into the translator's cache
        $this->injectDbTranslations();

        $this->configureDynamicMail();
        $this->registerBladeDirectives();
    }

    /**
     * Load all UI translations from DB and merge them into
     * the translator's loaded cache, bypassing the broken loader chain.
     */
    private function injectDbTranslations(): void
    {
        try {
            if (!\Schema::hasTable('ui_translations')) {
                self::$injectDebug['status'] = 'no_table';
                return;
            }

            $dbTranslations = \App\Models\UiTranslation::all();
            self::$injectDebug['db_count'] = $dbTranslations->count();

            // Build nested arrays per locale
            $overrides = [];
            foreach ($dbTranslations as $t) {
                $overrides[$t->locale][$t->group][$t->key] = $t->value;
            }
            self::$injectDebug['locales'] = array_keys($overrides);
            self::$injectDebug['groups'] = array_keys($overrides['nl'] ?? []);

            // Get the translator and inject into its loaded cache
            $translator = app('translator');

            // Try to find the loaded property (might be in parent class)
            $loadedRef = null;
            $class = new \ReflectionClass($translator);
            while ($class) {
                if ($class->hasProperty('loaded')) {
                    $loadedRef = $class->getProperty('loaded');
                    break;
                }
                $class = $class->getParentClass();
            }

            if (!$loadedRef) {
                self::$injectDebug['error'] = 'loaded_property_not_found';
                return;
            }

            $loadedRef->setAccessible(true);
            $loaded = $loadedRef->getValue($translator);
            self::$injectDebug['loaded_before'] = array_keys($loaded);

            foreach ($overrides as $locale => $groups) {
                foreach ($groups as $group => $keys) {
                    if (!isset($loaded['*'][$group][$locale])) {
                        $loaded['*'][$group][$locale] = [];
                    }
                    $loaded['*'][$group][$locale] = array_replace(
                        $loaded['*'][$group][$locale],
                        $keys
                    );
                }
            }

            $loadedRef->setValue($translator, $loaded);

            // Verify
            $loadedAfter = $loadedRef->getValue($translator);
            self::$injectDebug['loaded_after_keys'] = array_keys($loadedAfter);
            self::$injectDebug['nl_ui_view_fees'] = $loadedAfter['*']['ui']['nl']['view_fees'] ?? 'NOT_SET';
            self::$injectDebug['status'] = 'success';

        } catch (\Throwable $e) {
            self::$injectError = get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
            self::$injectDebug['exception'] = self::$injectError;
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
