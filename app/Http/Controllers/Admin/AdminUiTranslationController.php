<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UiTranslation;
use Illuminate\Http\Request;

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
            ->get();

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
            if (!$hasLabel) {
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
            if (!empty($label)) {
                UiTranslation::where('group', $group)
                    ->where('key', $key)
                    ->update(['label' => $label]);
            }
        }

        UiTranslation::clearCache();

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
}
