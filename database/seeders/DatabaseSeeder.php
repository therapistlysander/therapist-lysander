<?php

namespace Database\Seeders;

use Database\Seeders\UiTranslationSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // AdminUserSeeder::class,
            // PageSectionSeeder::class,
            // SeoSettingSeeder::class,
            // SiteSettingSeeder::class,
            // TestimonialSeeder::class,
            // FaqSeeder::class,
            // UiTranslationSeeder::class,
        ]);
    }
}
