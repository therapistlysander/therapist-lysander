<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UiTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Lang;

class AdminUiTranslationController extends Controller
{
    private array $groupLabels = [
        'nav' => 'Navigation',
        'footer' => 'Footer',
        'layout' => 'Layout & SEO',
        'page_title' => 'Page Titles',
        'common' => 'Common / Shared',
        'home' => 'Home Page',
        'about' => 'About Page',
        'approach' => 'Approach Page',
        'training' => 'Training Page',
        'testimonials' => 'Testimonials Page',
        'fees' => 'Fees Page',
        'contact' => 'Contact Page',
        'faq' => 'FAQ Page',
        'booking' => 'Booking Page',
        'language' => 'Language Switcher',
    ];

    public function index()
    {
        $groups = UiTranslation::select('group')
            ->selectRaw('COUNT(*) as key_count')
            ->selectRaw('COUNT(DISTINCT locale) as locale_count')
            ->groupBy('group')
            ->orderBy('group')
            ->get()
            ->keyBy('group');

        // Merge in file-based groups that have no DB rows yet, so every
        // translation group is reachable from the CMS.
        foreach ($this->fileGroups() as $group => $keyCount) {
            if (! $groups->has($group)) {
                $groups->put($group, (object) [
                    'group' => $group,
                    'key_count' => $keyCount,
                    'locale_count' => count(config('app.supported_locales', ['en', 'nl'])),
                ]);
            }
        }

        $groups = $groups->sortKeys()->values();

        $groupLabels = $this->groupLabels;

        return view('admin.pages.ui-translations.index', compact('groups', 'groupLabels'));
    }

    public function edit(string $group)
    {
        $translations = UiTranslation::where('group', $group)
            ->orderBy('key')
            ->get()
            ->groupBy('key');

        $groupLabel = $this->groupLabels[$group] ?? ucfirst(str_replace('_', ' ', $group));

        // Auto-generate labels for any keys that don't have one yet
        foreach ($translations as $key => $localeRows) {
            $hasLabel = $localeRows->firstWhere('label', '!=', null) !== null;
            if (! $hasLabel) {
                $generatedLabel = $this->generateLabel($key);
                foreach ($localeRows as $row) {
                    if (empty($row->label)) {
                        $row->label = $generatedLabel;
                        $row->save();
                    }
                }
            }
        }

        // Re-fetch after label generation
        $translations = UiTranslation::where('group', $group)
            ->orderBy('key')
            ->get()
            ->groupBy('key');

        // Merge in file-based keys that have no DB row yet (e.g. newly added
        // labels in lang/{locale}/ui.php), so they can be edited via the CMS.
        // DB rows keep precedence; file values are shown as non-persisted rows.
        foreach ($this->fileTranslationsForGroup($group) as $key => $locales) {
            if ($translations->has($key)) {
                continue;
            }
            $rows = collect();
            foreach ($locales as $locale => $value) {
                $rows->push(new UiTranslation([
                    'group' => $group,
                    'key' => $key,
                    'locale' => $locale,
                    'value' => $value,
                    'label' => $this->generateLabel($key),
                ]));
            }
            $translations->put($key, $rows);
        }

        $translations = $translations->sortKeys();

        return view('admin.pages.ui-translations.edit', compact('translations', 'group', 'groupLabel'));
    }

    public function update(Request $request, string $group)
    {
        $data = $request->input('translations', []);

        foreach ($data as $key => $locales) {
            foreach ($locales as $locale => $value) {
                UiTranslation::updateOrCreate(
                    [
                        'group' => $group,
                        'key' => $key,
                        'locale' => $locale,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }

        // Also update labels if provided
        $labels = $request->input('labels', []);
        foreach ($labels as $key => $label) {
            if (! empty($label)) {
                UiTranslation::where('group', $group)
                    ->where('key', $key)
                    ->update(['label' => $label]);
            }
        }

        UiTranslation::clearCache();

        // Clear all Laravel caches so translation changes reflect immediately
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        // Reset OPcache so fresh PHP files are used
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        return redirect()
            ->route('admin.ui-translations.edit', $group)
            ->with('success', 'Translations saved successfully.');
    }

    /**
     * Generate a human-readable label from a translation key.
     * e.g. "booking_cta" => "Booking Cta", "how_it_works" => "How It Works"
     */
    private function generateLabel(string $key): string
    {
        // Replace underscores with spaces and title-case each word
        return ucwords(str_replace('_', ' ', $key));
    }

    /**
     * File-based translation groups from lang/{locale}/ui.php with key counts.
     */
    private function fileGroups(): array
    {
        $groups = [];
        foreach (config('app.supported_locales', ['en', 'nl']) as $locale) {
            foreach ((array) Lang::get('ui', [], $locale) as $group => $keys) {
                if (is_array($keys)) {
                    $groups[$group] = max($groups[$group] ?? 0, count(Arr::dot($keys)));
                }
            }
        }

        return $groups;
    }

    /**
     * File-based translations for one group, flattened to dot-notation keys:
     * [key => [locale => value]].
     */
    private function fileTranslationsForGroup(string $group): array
    {
        $result = [];
        foreach (config('app.supported_locales', ['en', 'nl']) as $locale) {
            $groupData = Lang::get('ui.'.$group, [], $locale);
            if (! is_array($groupData)) {
                continue;
            }
            foreach (Arr::dot($groupData) as $key => $value) {
                if (is_scalar($value)) {
                    $result[$key][$locale] = (string) $value;
                }
            }
        }

        return $result;
    }
}
