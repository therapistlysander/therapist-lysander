<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix testimonials and faqs columns to be JSON type for Spatie Translatable.
     * Uses raw SQL because doctrine/dbal change() is unreliable for text->json on MySQL.
     */
    public function up(): void
    {
        // Fix testimonials table
        $columns = ['headline', 'body', 'quote', 'short_description'];
        foreach ($columns as $col) {
            try {
                DB::statement("ALTER TABLE testimonials MODIFY COLUMN `{$col}` JSON NULL");
            } catch (\Throwable $e) {
                // Column might not exist yet, that's ok
            }
        }

        // Fix faqs table
        try {
            DB::statement('ALTER TABLE faqs MODIFY COLUMN `question` JSON NOT NULL');
            DB::statement('ALTER TABLE faqs MODIFY COLUMN `answer` JSON NOT NULL');
        } catch (\Throwable $e) {
            // Columns might not exist yet
        }
    }

    public function down(): void
    {
        // No safe rollback for JSON -> text conversion
    }
};
