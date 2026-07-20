<?php

use App\Models\SeoSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Align SEO settings with the page_keys used by FrontendController and
     * refresh the English + Dutch link-preview text (Open Graph / meta) for
     * every public page.
     *
     * Background:
     *  - FrontendController queries page_keys: home, about, approach, training,
     *    testimonials, fees, faq, contact, booking.
     *  - The stored records used the legacy keys "trauma-approach" and
     *    "fees-process", and had no records for training/testimonials/contact.
     *    Those pages therefore rendered no og:title / og:description at all.
     *
     * This migration renames the legacy keys to the canonical ones, creates the
     * missing records, and sets the exact copy requested by the client for both
     * locales. It only writes the specified fields, so any other stored
     * translations remain untouched. Safe and idempotent against live data.
     */
    public function up(): void
    {
        // 1. Rename legacy keys to the canonical keys used by the controller.
        $this->renameKey('trauma-approach', 'approach');
        $this->renameKey('fees-process', 'fees');

        // 2. Upsert link-preview copy for every page (only specified fields).
        foreach ($this->pages() as $pageKey => $data) {
            $seo = SeoSetting::firstOrNew(['page_key' => $pageKey]);

            foreach (['en', 'nl'] as $locale) {
                if (empty($data[$locale])) {
                    continue;
                }
                foreach (['meta_title', 'meta_description', 'og_title', 'og_description'] as $col) {
                    if (array_key_exists($col, $data[$locale])) {
                        $seo->setTranslation($col, $locale, $data[$locale][$col]);
                    }
                }
            }

            $seo->save();
        }
    }

    public function down(): void
    {
        // Reverse only the key renames; content is left in place (best effort).
        $this->renameKey('approach', 'trauma-approach');
        $this->renameKey('fees', 'fees-process');
    }

    /**
     * Rename a SEO record's page_key, but only if the source exists and the
     * destination key is not already taken (avoids unique-constraint errors).
     */
    private function renameKey(string $from, string $to): void
    {
        if (SeoSetting::where('page_key', $to)->exists()) {
            return;
        }

        $record = SeoSetting::where('page_key', $from)->first();
        if ($record) {
            $record->page_key = $to;
            $record->save();
        }
    }

    /**
     * @return array<string, array{en?: array<string, string>, nl?: array<string, string>}>
     */
    private function pages(): array
    {
        return [
            'home' => [
                'en' => [
                    'meta_title'       => 'Lysander Verschuur — Psychologist & Trauma Therapist',
                    'og_title'         => 'Lysander Verschuur — Psychologist & Trauma Therapist',
                    'meta_description' => 'Client-centered, evidence-based therapy for emotional wellbeing, resilience, and meaningful psychological change.',
                    'og_description'   => 'Client-centered, evidence-based therapy for emotional wellbeing, resilience, and meaningful psychological change.',
                ],
                'nl' => [
                    'meta_title'       => 'Lysander Verschuur — Psycholoog & Traumatherapeut',
                    'og_title'         => 'Lysander Verschuur — Psycholoog & Traumatherapeut',
                    'meta_description' => 'Persoonlijke, evidence-based therapie voor psychische klachten, persoonlijke groei en blijvende verandering.',
                    'og_description'   => 'Persoonlijke, evidence-based therapie voor psychische klachten, persoonlijke groei en blijvende verandering.',
                ],
            ],

            'approach' => [
                'en' => [
                    'meta_title'       => 'Trauma & My Approach — Lysander Verschuur',
                    'og_title'         => 'Trauma & My Approach — Lysander Verschuur',
                    'meta_description' => 'Trauma-focused therapy that combines evidence-based methods with a warm, collaborative, and personalized approach.',
                    'og_description'   => 'Trauma-focused therapy that combines evidence-based methods with a warm, collaborative, and personalized approach.',
                ],
                'nl' => [
                    'meta_title'       => 'Trauma & Aanpak — Lysander Verschuur',
                    'og_title'         => 'Trauma & Aanpak — Lysander Verschuur',
                    'meta_description' => 'Mijn visie op trauma, psychotherapie en een persoonlijke aanpak die past bij jouw situatie.',
                    'og_description'   => 'Mijn visie op trauma, psychotherapie en een persoonlijke aanpak die past bij jouw situatie.',
                ],
            ],

            'testimonials' => [
                'en' => [
                    'meta_title'       => 'Client Experiences — Lysander Verschuur',
                    'og_title'         => 'Client Experiences — Lysander Verschuur',
                    'meta_description' => 'Read genuine client experiences and discover how therapy has helped others recover and grow.',
                    'og_description'   => 'Read genuine client experiences and discover how therapy has helped others recover and grow.',
                ],
                'nl' => [
                    'meta_title'       => 'Cliëntervaringen — Lysander Verschuur',
                    'og_title'         => 'Cliëntervaringen — Lysander Verschuur',
                    'meta_description' => 'Lees de ervaringen van cliënten die hun verhaal wilden delen.',
                    'og_description'   => 'Lees de ervaringen van cliënten die hun verhaal wilden delen.',
                ],
            ],

            'training' => [
                'en' => [
                    'meta_title'       => 'Clinical Training — Lysander Verschuur',
                    'og_title'         => 'Clinical Training — Lysander Verschuur',
                    'meta_description' => "Learn about Lysander Verschuur's clinical training, professional background, and therapeutic approach.",
                    'og_description'   => "Learn about Lysander Verschuur's clinical training, professional background, and therapeutic approach.",
                ],
                'nl' => [
                    'meta_title'       => 'Opleiding & Specialisatie — Lysander Verschuur',
                    'og_title'         => 'Opleiding & Specialisatie — Lysander Verschuur',
                    'meta_description' => 'Meer over mijn opleiding, klinische ervaring en de behandelmethoden waarmee ik werk.',
                    'og_description'   => 'Meer over mijn opleiding, klinische ervaring en de behandelmethoden waarmee ik werk.',
                ],
            ],

            'fees' => [
                'en' => [
                    'meta_title'       => 'Fees & Process — Lysander Verschuur',
                    'og_title'         => 'Fees & Process — Lysander Verschuur',
                    'meta_description' => 'Everything you need to know before starting therapy.',
                    'og_description'   => 'Everything you need to know before starting therapy.',
                ],
                'nl' => [
                    'meta_title'       => 'Tarieven & Traject — Lysander Verschuur',
                    'og_title'         => 'Tarieven & Traject — Lysander Verschuur',
                    'meta_description' => 'Alles wat je moet weten over tarieven, het therapietraject en praktische informatie.',
                    'og_description'   => 'Alles wat je moet weten over tarieven, het therapietraject en praktische informatie.',
                ],
            ],

            'faq' => [
                'en' => [
                    'meta_title'       => 'FAQ — Lysander Verschuur',
                    'meta_description' => 'Answers to the most common questions about starting therapy.',
                    'og_description'   => 'Answers to the most common questions about starting therapy.',
                ],
                'nl' => [
                    'meta_title'       => 'Veelgestelde Vragen — Lysander Verschuur',
                    'meta_description' => 'Antwoorden op veelgestelde vragen over therapie en het starten van een traject.',
                    'og_description'   => 'Antwoorden op veelgestelde vragen over therapie en het starten van een traject.',
                ],
            ],

            'contact' => [
                'en' => [
                    'meta_title'       => 'Contact — Lysander Verschuur',
                    'og_title'         => 'Contact — Lysander Verschuur',
                    'meta_description' => 'Get in touch to ask a question or schedule your free introductory call.',
                    'og_description'   => 'Get in touch to ask a question or schedule your free introductory call.',
                ],
                'nl' => [
                    'meta_title'       => 'Contact — Lysander Verschuur',
                    'og_title'         => 'Contact — Lysander Verschuur',
                    'meta_description' => 'Heb je een vraag of wil je kennismaken? Neem gerust contact op of plan een gratis kennismakingsgesprek.',
                    'og_description'   => 'Heb je een vraag of wil je kennismaken? Neem gerust contact op of plan een gratis kennismakingsgesprek.',
                ],
            ],

            'booking' => [
                'en' => [
                    'meta_title'       => 'Book a Session — Lysander Verschuur',
                    'meta_description' => 'Book a free 30-minute introductory call to explore whether therapy is the right fit for you.',
                    'og_description'   => 'Book a free 30-minute introductory call to explore whether therapy is the right fit for you.',
                ],
                'nl' => [
                    'meta_title'       => 'Afspraak Maken — Lysander Verschuur',
                    'meta_description' => 'Boek een gratis kennismakingsgesprek van 30 minuten om te ontdekken of therapie bij jou past.',
                    'og_description'   => 'Boek een gratis kennismakingsgesprek van 30 minuten om te ontdekken of therapie bij jou past.',
                ],
            ],
        ];
    }
};
