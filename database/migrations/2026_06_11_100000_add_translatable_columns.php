<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columns that need to be converted to translatable JSON.
     * Format: 'table' => ['column1', 'column2', ...]
     */
    protected array $translatableColumns = [
        'faqs'          => ['question', 'answer'],
        'testimonials'  => ['headline', 'body', 'quote'],
        'seo_settings'  => ['meta_title', 'meta_description', 'og_title', 'og_description'],
    ];

    public function up(): void
    {
        // 1. Wrap PageSection.content (already JSON) into {"en": <existing>}
        $this->wrapJsonColumn('page_sections', 'content', true);

        // 2. Wrap string/text columns into {"en": "<value>"} and change type to JSON
        foreach ($this->translatableColumns as $table => $columns) {
            foreach ($columns as $column) {
                $this->wrapJsonColumn($table, $column, false);
            }
        }

        // 3. Change column types to JSON (skip on SQLite)
        if ($this->isMysql()) {
            foreach ($this->translatableColumns as $table => $columns) {
                Schema::table($table, function ($blueprint) use ($columns) {
                    foreach ($columns as $column) {
                        $blueprint->json($column)->change();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        // 1. Unwrap PageSection.content: extract "en" key back to root
        $this->unwrapJsonColumn('page_sections', 'content', true);

        // 2. Unwrap string/text columns: extract "en" key back to plain string
        foreach ($this->translatableColumns as $table => $columns) {
            foreach ($columns as $column) {
                $this->unwrapJsonColumn($table, $column, false);
            }
        }

        // 3. Revert column types (skip on SQLite)
        if ($this->isMysql()) {
            $revertTypes = [
                'faqs'          => ['question' => 'string', 'answer' => 'text'],
                'testimonials'  => ['headline' => 'string', 'body' => 'text', 'quote' => 'text'],
                'seo_settings'  => ['meta_title' => 'string', 'meta_description' => 'text', 'og_title' => 'string', 'og_description' => 'text'],
            ];

            foreach ($revertTypes as $table => $columns) {
                Schema::table($table, function ($blueprint) use ($columns) {
                    foreach ($columns as $column => $type) {
                        $blueprint->{$type}($column)->change();
                    }
                });
            }
        }
    }

    /**
     * Wrap existing column values into {"en": <value>} format.
     */
    protected function wrapJsonColumn(string $table, string $column, bool $isObject): void
    {
        $rows = DB::table($table)->get();

        foreach ($rows as $row) {
            $raw = $row->{$column};

            if ($raw === null || $raw === '') {
                continue;
            }

            if ($isObject) {
                // Column already stores JSON objects (like PageSection.content)
                $decoded = json_decode($raw, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue;
                }

                // Skip if already wrapped (has 'en' key at top level)
                if (is_array($decoded) && array_key_exists('en', $decoded)) {
                    continue;
                }

                $wrapped = json_encode(['en' => $decoded], JSON_UNESCAPED_UNICODE);
            } else {
                // Column stores plain strings — wrap as {"en": "string"}
                // Skip if already looks like translatable JSON
                $decoded = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && array_key_exists('en', $decoded)) {
                    continue;
                }

                $wrapped = json_encode(['en' => $raw], JSON_UNESCAPED_UNICODE);
            }

            DB::table($table)->where('id', $row->id)->update([$column => $wrapped]);
        }
    }

    /**
     * Unwrap translatable JSON back to original format (extract 'en' key).
     */
    protected function unwrapJsonColumn(string $table, string $column, bool $isObject): void
    {
        $rows = DB::table($table)->get();

        foreach ($rows as $row) {
            $raw = $row->{$column};

            if ($raw === null || $raw === '') {
                continue;
            }

            $decoded = json_decode($raw, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                continue;
            }

            $enValue = $decoded['en'] ?? null;

            if ($enValue === null) {
                continue;
            }

            if ($isObject) {
                // Restore the original JSON object
                $restored = json_encode($enValue, JSON_UNESCAPED_UNICODE);
            } else {
                // Restore the original plain string
                $restored = is_string($enValue) ? $enValue : json_encode($enValue, JSON_UNESCAPED_UNICODE);
            }

            DB::table($table)->where('id', $row->id)->update([$column => $restored]);
        }
    }

    protected function isMysql(): bool
    {
        return Schema::getConnection()->getDriverName() === 'mysql';
    }
};
