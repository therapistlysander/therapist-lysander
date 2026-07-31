<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Shorten the Dutch "view all FAQs" button label on the contact page.
     *
     * Background:
     *  - The contact page FAQ button reads its label from the ui_translations
     *    DB override (which wins over the lang file).
     *  - The Dutch value "Bekijk alle veelgestelde vragen" is long enough to
     *    fill the entire row on small phones, making the button look too wide.
     *  - Shortening it to "Veelgestelde vragen" (matching the site's nav label)
     *    lets the button hug its content and sit at a normal, compact width.
     *
     * Only updates the row when it still holds the original long value, so any
     * later manual edit from the admin panel is preserved. Safe and idempotent.
     */
    public function up(): void
    {
        if (! $this->hasTable()) {
            return;
        }

        DB::table('ui_translations')
            ->where('locale', 'nl')
            ->where('group', 'contact')
            ->where('key', 'view_faqs')
            ->where('value', 'Bekijk alle veelgestelde vragen')
            ->update(['value' => 'Veelgestelde vragen']);
    }

    public function down(): void
    {
        if (! $this->hasTable()) {
            return;
        }

        DB::table('ui_translations')
            ->where('locale', 'nl')
            ->where('group', 'contact')
            ->where('key', 'view_faqs')
            ->where('value', 'Veelgestelde vragen')
            ->update(['value' => 'Bekijk alle veelgestelde vragen']);
    }

    private function hasTable(): bool
    {
        return \Schema::hasTable('ui_translations');
    }
};
