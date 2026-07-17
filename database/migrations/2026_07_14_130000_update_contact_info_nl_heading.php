<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Update the Dutch heading of the "contact_info" page section from
 * "Contactgegevens" to the more inviting "Neem contact op".
 *
 * The contact page heading is rendered from the page_sections table
 * (content JSON, keyed by locale via spatie/laravel-translatable). The
 * PopulateDutchContent command source was updated too; this migration keeps
 * the existing DB row in sync so the change is reflected on the live site.
 *
 * Idempotent: re-running simply re-sets the same value.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('page_sections')) {
            return;
        }

        $section = DB::table('page_sections')
            ->where('section_key', 'contact_info')
            ->first();

        if (!$section) {
            return;
        }

        $content = json_decode($section->content ?? '', true);

        if (!is_array($content) || !isset($content['nl']) || !is_array($content['nl'])) {
            return;
        }

        $content['nl']['heading'] = 'Neem contact op';

        DB::table('page_sections')
            ->where('id', $section->id)
            ->update([
                'content'    => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // No-op: previous copy is superseded and not restored intentionally.
    }
};
