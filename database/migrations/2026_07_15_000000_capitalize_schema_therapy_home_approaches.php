<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Capitalize the treatment name "Schema therapy" -> "Schema Therapy"
     * within the home page "Therapeutic approaches" section, for consistency
     * with the other therapeutic approaches (CBT, ACT, EMDR).
     *
     * Targeted and idempotent: only touches the home_approaches section content
     * and only affects the exact lowercase phrase, so it is safe to run against
     * live production data.
     */
    public function up(): void
    {
        $this->replaceInHomeApproaches('Schema therapy', 'Schema Therapy');
    }

    public function down(): void
    {
        // Reverse the capitalization within the same section only.
        $this->replaceInHomeApproaches('Schema Therapy', 'Schema therapy');
    }

    private function replaceInHomeApproaches(string $search, string $replace): void
    {
        $section = DB::table('page_sections')
            ->where('section_key', 'home_approaches')
            ->first();

        if (! $section) {
            return;
        }

        $content = $section->content;

        if ($content === null || ! str_contains($content, $search)) {
            return;
        }

        $updated = str_replace($search, $replace, $content);

        DB::table('page_sections')
            ->where('id', $section->id)
            ->update([
                'content'    => $updated,
                'updated_at' => now(),
            ]);
    }
};
