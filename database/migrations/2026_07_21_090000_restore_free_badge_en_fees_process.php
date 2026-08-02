<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Restore the "Free" badge and "· Free" duration suffix on the EN
 * introductory-call process step (fees_process.steps[0]) per client
 * feedback — the green badge should match the Dutch "Gratis" version.
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

        $process = DB::table('page_sections')
            ->where('section_key', 'fees_process')
            ->first();

        if (!$process) {
            return;
        }

        $content = json_decode($process->content ?? '', true);

        if (is_array($content) && isset($content['en']['steps']) && is_array($content['en']['steps'])) {
            $steps = &$content['en']['steps'];
            if (isset($steps[0])) {
                $steps[0]['badge'] = 'Free';
                $steps[0]['duration'] = '30 minutes · Free';
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

    public function down(): void
    {
        // No-op: previous state is superseded.
    }
};
