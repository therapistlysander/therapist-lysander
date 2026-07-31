<?php

use App\Models\UiTranslation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Correct the Dutch footer navigation labels (these keys are shared with
     * the main navigation menu, so the fix keeps the footer, nav menu and page
     * titles consistent):
     *
     *  - nav.testimonials: "Cliënt ervaringen" -> "Cliënt Ervaringen"
     *    (proper capitalization, consistent with the other footer links).
     *  - nav.fees: force to "Tarieven & Traject" — matches the nav menu and
     *    page title, removing any lingering "Bekijk ..." / uppercase variant
     *    that may still exist in production.
     *
     * Idempotent: uses updateOrCreate keyed on (locale, group, key).
     */
    public function up(): void
    {
        $rows = [
            ['locale' => 'nl', 'group' => 'nav', 'key' => 'testimonials', 'value' => 'Cliënt Ervaringen'],
            ['locale' => 'nl', 'group' => 'nav', 'key' => 'fees',         'value' => 'Tarieven & Traject'],
        ];

        foreach ($rows as $row) {
            UiTranslation::updateOrCreate(
                ['locale' => $row['locale'], 'group' => $row['group'], 'key' => $row['key']],
                ['value' => $row['value']]
            );
        }

        UiTranslation::clearCache();
    }

    public function down(): void
    {
        // Restore the previous Dutch testimonials label. The fees label is left
        // as-is (its previous production value is not reliably known).
        UiTranslation::where('locale', 'nl')
            ->where('group', 'nav')
            ->where('key', 'testimonials')
            ->update(['value' => 'Cliënt ervaringen']);

        UiTranslation::clearCache();
    }
};
