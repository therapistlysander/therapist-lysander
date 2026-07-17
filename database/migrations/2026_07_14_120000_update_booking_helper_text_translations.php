<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Update the DB translation overrides for the booking Step 3 helper text and
 * the Dutch "what brings you here" intro text.
 *
 * These keys are overridden in the ui_translations table (via the admin UI
 * translations panel), which takes precedence over the lang/*.php files. The
 * lang files were already updated; this migration keeps the DB overrides in
 * sync so the changes are reflected on the live site.
 *
 * Idempotent: re-running simply re-sets the same values.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ui_translations')) {
            return;
        }

        $updates = [
            [
                'group'  => 'booking',
                'key'    => 'choose_time_desc',
                'locale' => 'en',
                'value'  => "Select a day to view the available time slots. If you don't see a suitable time, feel free to contact me.",
            ],
            [
                'group'  => 'booking',
                'key'    => 'choose_time_desc',
                'locale' => 'nl',
                'value'  => 'Selecteer een dag om de beschikbare tijden te zien. Zie je geen geschikt tijdstip? Neem gerust contact met me op.',
            ],
            [
                'group'  => 'booking',
                'key'    => 'what_brings_desc',
                'locale' => 'nl',
                'value'  => 'Een paar zinnen zijn voldoende. Waar worstel je momenteel mee, en waar zou je hulp bij willen? Dit helpt ons eerste gesprek richting te geven.',
            ],
        ];

        foreach ($updates as $row) {
            // Only touch rows that already exist as overrides; if absent the
            // lang/*.php fallback already provides the correct value.
            DB::table('ui_translations')
                ->where('group', $row['group'])
                ->where('key', $row['key'])
                ->where('locale', $row['locale'])
                ->update([
                    'value'      => $row['value'],
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // No-op: previous copy is superseded and not restored intentionally.
    }
};
