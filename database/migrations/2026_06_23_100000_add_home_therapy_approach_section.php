<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Shift existing sections to make room for the new therapy_approach section
        DB::table('page_sections')
            ->where('page', 'home')
            ->where('sort_order', '>=', 5)
            ->increment('sort_order');

        // Insert the new "What Therapy With Me Is Like" section
        DB::table('page_sections')->insert([
            'page'        => 'home',
            'section_key' => 'home_therapy_approach',
            'label'       => 'Therapy With Me Is Like',
            'sort_order'  => 5,
            'is_active'   => 1,
            'content'     => json_encode([
                'en' => [
                    'subheading' => 'Therapy Approach',
                    'heading'    => 'What Therapy With Me Is Like',
                    'body'       => 'Therapy with me is warm, direct, and practical. Together, we explore the patterns that keep you stuck, process difficult emotions, and build a life that feels more aligned with who you truly are.',
                    'cards'      => [
                        [
                            'title'       => 'Safe and non-judgmental',
                            'description' => 'A space where you can speak openly, without fear of judgment or pressure to perform.',
                        ],
                        [
                            'title'       => 'Evidence-based and practical',
                            'description' => 'Grounded in proven therapeutic methods, applied in a way that makes sense for your life.',
                        ],
                        [
                            'title'       => 'Focused on lasting change',
                            'description' => 'Not just symptom relief — working toward meaningful, sustainable psychological change.',
                        ],
                    ],
                ],
                'nl' => [
                    'subheading' => 'Therapieaanpak',
                    'heading'    => 'Hoe Therapie Bij Mij Is',
                    'body'       => 'Therapie bij mij is warm, direct en praktisch. Samen verkennen we de patronen die je vasthouden, verwerken we moeilijke emoties, en bouwen we een leven dat beter past bij wie je werkelijk bent.',
                    'cards'      => [
                        [
                            'title'       => 'Veilig en oordeelvrij',
                            'description' => 'Een ruimte waar je open kunt spreken, zonder angst voor oordeel of druk om te presteren.',
                        ],
                        [
                            'title'       => 'Evidence-based en praktisch',
                            'description' => 'Gegrond in bewezen therapeutische methoden, toegepast op een manier die past bij jouw leven.',
                        ],
                        [
                            'title'       => 'Gericht op blijvende verandering',
                            'description' => 'Niet alleen symptoombestrijding — werken aan betekenisvolle, duurzame psychologische verandering.',
                        ],
                    ],
                ],
            ], JSON_UNESCAPED_UNICODE),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('page_sections')
            ->where('section_key', 'home_therapy_approach')
            ->delete();

        DB::table('page_sections')
            ->where('page', 'home')
            ->where('sort_order', '>', 5)
            ->decrement('sort_order');
    }
};
