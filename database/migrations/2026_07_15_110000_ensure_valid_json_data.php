<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ensure all existing data in JSON columns is valid JSON.
     * Converts plain text to JSON format: {"en": "text"}
     */
    public function up(): void
    {
        // Fix testimonials
        $this->fixJsonColumn('testimonials', 'headline');
        $this->fixJsonColumn('testimonials', 'body');
        $this->fixJsonColumn('testimonials', 'quote');
        $this->fixJsonColumn('testimonials', 'short_description');

        // Fix faqs
        $this->fixJsonColumn('faqs', 'question');
        $this->fixJsonColumn('faqs', 'answer');
    }

    private function fixJsonColumn(string $table, string $column): void
    {
        try {
            // Get all rows where the column is not null
            $rows = DB::table($table)->whereNotNull($column)->get([$column, 'id']);

            foreach ($rows as $row) {
                $value = $row->$column;

                // Skip if already valid JSON object
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (is_array($decoded) && isset($decoded['en'])) {
                        continue; // Already valid
                    }

                    // Convert plain text to JSON
                    $json = json_encode(['en' => $value], JSON_UNESCAPED_UNICODE);
                    DB::table($table)->where('id', $row->id)->update([$column => $json]);
                }
            }
        } catch (\Throwable $e) {
            // Table or column might not exist
        }
    }

    public function down(): void
    {
        // No rollback needed
    }
};
