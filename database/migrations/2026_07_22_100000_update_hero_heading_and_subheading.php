<?php

use App\Models\PageSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Update the homepage hero heading and subheading per client request.
     *
     * EN: "Therapy That Helps You Move Forward" / "Online Worldwide • In Person in Amsterdam"
     * NL: "Psychotherapie voor blijvende verandering" / "Online • In mijn praktijk in Amsterdam"
     */
    public function up(): void
    {
        $hero = PageSection::where('page', 'home')->where('section_key', 'home_hero')->first();

        if (!$hero) {
            return;
        }

        // Update English content
        $hero->setTranslation('content', 'en', array_merge(
            $hero->getTranslation('content', 'en', false) ?? [],
            [
                'heading'    => 'Therapy That Helps You Move Forward',
                'subheading' => 'Online Worldwide • In Person in Amsterdam',
            ]
        ));

        // Update Dutch content
        $hero->setTranslation('content', 'nl', array_merge(
            $hero->getTranslation('content', 'nl', false) ?? [],
            [
                'heading'    => 'Psychotherapie voor blijvende verandering',
                'subheading' => 'Online • In mijn praktijk in Amsterdam',
            ]
        ));

        $hero->save();
    }

    public function down(): void
    {
        $hero = PageSection::where('page', 'home')->where('section_key', 'home_hero')->first();

        if (!$hero) {
            return;
        }

        $hero->setTranslation('content', 'en', array_merge(
            $hero->getTranslation('content', 'en', false) ?? [],
            [
                'heading'    => 'Online therapy for adults ready to move forward.',
                'subheading' => 'Psychologist & Trauma Therapist',
            ]
        ));

        $hero->setTranslation('content', 'nl', array_merge(
            $hero->getTranslation('content', 'nl', false) ?? [],
            [
                'heading'    => 'Online therapie voor volwassenen die vooruit willen.',
                'subheading' => 'Psycholoog & Traumatherapeut',
            ]
        ));

        $hero->save();
    }
};
