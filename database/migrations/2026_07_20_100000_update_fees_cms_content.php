<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 1. Bind the Session Fee body text to CMS content (fees_pricing.body)
 *    and update the copy per client request.
 * 2. Remove the "Free" badge and "· Free" suffix from the EN introductory
 *    call process step (fees_process.steps[0]).
 *
 * Idempotent: re-running simply re-sets the same values.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('page_sections')) {
            return;
        }

        // ── fees_pricing: update body text (EN + NL) ──────────────────────
        $pricing = DB::table('page_sections')
            ->where('section_key', 'fees_pricing')
            ->first();

        if ($pricing) {
            $content = json_decode($pricing->content ?? '', true);

            if (is_array($content)) {
                if (isset($content['en']) && is_array($content['en'])) {
                    $content['en']['body'] = '<p>Individual therapy sessions last 60 minutes. A free, no-obligation 30-minute introductory call is included before treatment.</p>';
                }
                if (isset($content['nl']) && is_array($content['nl'])) {
                    $content['nl']['body'] = '<p>Individuele therapiesessies duren 60 minuten. Daarnaast bied ik een gratis en vrijblijvend kennismakingsgesprek van 30 minuten aan.</p>';
                }

                DB::table('page_sections')
                    ->where('id', $pricing->id)
                    ->update([
                        'content'    => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'updated_at' => now(),
                    ]);
            }
        }

        // ── fees_process: remove EN badge + "· Free" from step 1 ─────────
        $process = DB::table('page_sections')
            ->where('section_key', 'fees_process')
            ->first();

        if ($process) {
            $content = json_decode($process->content ?? '', true);

            if (is_array($content) && isset($content['en']['steps']) && is_array($content['en']['steps'])) {
                $steps = &$content['en']['steps'];
                if (isset($steps[0])) {
                    $steps[0]['badge'] = null;
                    $steps[0]['duration'] = '30 minutes';
                }
                unset($steps);

                DB::table('page_sections')
                    ->where('id', $process->id)
                    ->update([
                        'content'    => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    public function down(): void
    {
        // No-op: previous copy is superseded.
    }
};
