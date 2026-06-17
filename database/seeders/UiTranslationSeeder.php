<?php

namespace Database\Seeders;

use App\Models\UiTranslation;
use Illuminate\Database\Seeder;

class UiTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding UI translations from lang files...');

        $locales = ['en', 'nl'];
        $count = 0;

        foreach ($locales as $locale) {
            $filePath = lang_path("{$locale}/ui.php");
            
            if (!file_exists($filePath)) {
                $this->command->warn("  File not found: {$filePath}");
                continue;
            }

            $translations = require $filePath;
            
            if (!is_array($translations)) {
                $this->command->warn("  Invalid format: {$filePath}");
                continue;
            }

            $flattened = $this->flattenArray($translations);

            foreach ($flattened as $groupKey => $value) {
                // Parse "group.key" format
                $parts = explode('.', $groupKey, 2);
                if (count($parts) !== 2) continue;

                [$group, $key] = $parts;

                UiTranslation::updateOrCreate(
                    [
                        'group' => $group,
                        'key' => $key,
                        'locale' => $locale,
                    ],
                    [
                        'value' => $value,
                    ]
                );
                $count++;
            }

            $this->command->info("  Seeded {$locale}: " . count($flattened) . " keys");
        }

        $this->command->info("Done! Total records: {$count}");
    }

    private function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = (string) $value;
            }
        }

        return $result;
    }
}
