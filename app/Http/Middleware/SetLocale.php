<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->detectLocale($request);
        app()->setLocale($locale);
        $request->session()->put('locale', $locale);
        URL::defaults(['locale' => $locale]);

        // Swap in DatabaseTranslationLoader for DB-driven UI translations
        $this->installDatabaseTranslationLoader();

        return $next($request);
    }

    private function installDatabaseTranslationLoader(): void
    {
        try {
            $translator = app('translator');

            // Check if loader is already our DB loader via reflection
            $loaderRef = new \ReflectionProperty($translator, 'loader');
            $loaderRef->setAccessible(true);
            $currentLoader = $loaderRef->getValue($translator);

            if (!($currentLoader instanceof \App\Translation\DatabaseTranslationLoader)) {
                $dbLoader = new \App\Translation\DatabaseTranslationLoader(
                    app('files'),
                    app()['path.lang']
                );
                $loaderRef->setValue($translator, $dbLoader);

                // Clear translator's internal loaded cache
                $loadedRef = new \ReflectionProperty($translator, 'loaded');
                $loadedRef->setAccessible(true);
                $loadedRef->setValue($translator, []);
            }
        } catch (\Throwable $e) {
            // Silently fail
        }
    }

    private function detectLocale(Request $request): string
    {
        $supported = config('app.supported_locales', ['en', 'nl']);

        // 1. URL segment (highest priority)
        $segment = $request->segment(1);
        if ($segment && in_array($segment, $supported, true)) {
            return $segment;
        }

        // 2. Session
        $sessionLocale = $request->session()->get('locale');
        if ($sessionLocale && in_array($sessionLocale, $supported, true)) {
            return $sessionLocale;
        }

        // 3. Accept-Language header
        $browserLocale = $this->parseAcceptLanguage($request);
        if ($browserLocale && in_array($browserLocale, $supported, true)) {
            return $browserLocale;
        }

        // 4. Default
        return config('app.locale', 'en');
    }

    private function parseAcceptLanguage(Request $request): ?string
    {
        $header = $request->header('Accept-Language');
        if (!$header) {
            return null;
        }

        $supported = config('app.supported_locales', ['en', 'nl']);

        // Parse Accept-Language: en-US,en;q=0.9,nl;q=0.8
        $locales = [];
        foreach (explode(',', $header) as $part) {
            $parts = explode(';', trim($part));
            $lang = strtolower(trim($parts[0]));
            $lang = explode('-', $lang)[0]; // Take primary language subtag
            $q = 1.0;
            if (isset($parts[1]) && preg_match('/q=([\d.]+)/', $parts[1], $m)) {
                $q = (float) $m[1];
            }
            if (in_array($lang, $supported, true)) {
                $locales[$lang] = $q;
            }
        }

        if (empty($locales)) {
            return null;
        }

        arsort($locales);

        return array_key_first($locales);
    }
}
