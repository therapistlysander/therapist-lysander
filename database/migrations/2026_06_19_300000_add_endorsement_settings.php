<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'group' => 'endorsement',
                'key'   => 'endorsement_heading',
                'value' => json_encode(['en' => 'Recommended by a Fellow Psychotherapist', 'nl' => 'Aanbevolen door een collega-psychotherapeut'], JSON_UNESCAPED_UNICODE),
                'type'  => 'json',
                'label' => 'Endorsement Heading',
            ],
            [
                'group' => 'endorsement',
                'key'   => 'endorsement_short_quote',
                'value' => json_encode(['en' => '"Professionalism, dedication, extensive experience, empathy, and a warm presence."', 'nl' => '"Professionaliteit, bezieling, ruime ervaring, empathie en een warme aanwezigheid."'], JSON_UNESCAPED_UNICODE),
                'type'  => 'json',
                'label' => 'Short Quote (homepage)',
            ],
            [
                'group' => 'endorsement',
                'key'   => 'endorsement_full_body',
                'value' => json_encode([
                    'en' => "Lysander comes highly recommended as a trauma therapist for those seeking depth, nuance, and genuine therapeutic expertise. He combines professionalism, dedication, and extensive experience with empathy and a warm, grounded presence.\n\nHe has an extensive therapeutic toolbox and continues to expand his knowledge and skills with great enthusiasm. This allows him to tailor his approach to each individual client and adapt creatively to their needs.\n\nI have personally experienced sessions with Lysander and found them to be incredibly valuable and impactful.",
                    'nl' => "Lysander is echt een aanrader als trauma therapeut als je voor diepgang en finesse gaat. Professionaliteit, bezieling, veel ervaring, in combinatie met empathie en warme aanwezigheid.\n\nHij heeft een flinke therapeutische toolbox die hij enthousiast verder blijft uitbreiden. Hierdoor weet hij mooi te improviseren en dingen op maat te maken voor zijn cliënten.\n\nIk heb zelf ook sessies van hem mogen ontvangen en heb die als enorm waardevol ervaren!"
                ], JSON_UNESCAPED_UNICODE),
                'type'  => 'text',
                'label' => 'Full Text (testimonials page)',
            ],
            [
                'group' => 'endorsement',
                'key'   => 'endorsement_attribution',
                'value' => json_encode(['en' => 'Stacey, BIG-registered Psychotherapist', 'nl' => 'Stacey, BIG-geregistreerd psychotherapeut'], JSON_UNESCAPED_UNICODE),
                'type'  => 'json',
                'label' => 'Attribution',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'endorsement_heading',
            'endorsement_short_quote',
            'endorsement_full_body',
            'endorsement_attribution',
        ])->delete();
    }
};
