<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoSettingSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_key'         => 'home',
                'meta_title'       => 'Lysander Verschuur — Psychologist & Trauma Therapist Amsterdam',
                'meta_description' => 'Lysander Verschuur is a registered psychologist and trauma therapist in Amsterdam offering EMDR, CBT, and trauma-focused therapy in Dutch and English.',
                'og_title'         => 'Lysander Verschuur — Psychologist & Trauma Therapist',
                'og_description'   => 'A safe, grounded space to work through trauma, anxiety, and the challenges keeping you stuck. Online and in-person sessions in Amsterdam.',
            ],
            [
                'page_key'         => 'about',
                'meta_title'       => 'About Lysander Verschuur — Psychologist & Trauma Therapist',
                'meta_description' => 'Learn about Lysander Verschuur\'s background, training, and approach to psychotherapy and trauma treatment in Amsterdam.',
                'og_title'         => 'About Lysander Verschuur',
                'og_description'   => 'Registered psychologist and trauma therapist based in Amsterdam, working with adults in Dutch and English.',
            ],
            [
                'page_key'         => 'fees-process',
                'meta_title'       => 'Fees & Process — Therapy with Lysander Verschuur',
                'meta_description' => 'Transparent information about session fees, what to expect from the therapy process, and how to get started.',
                'og_title'         => 'Fees & Process — Lysander Verschuur',
                'og_description'   => 'Find out about therapy session fees, the intake process, and what working together looks like.',
            ],
            [
                'page_key'         => 'booking',
                'meta_title'       => 'Book an Intro Call — Lysander Verschuur',
                'meta_description' => 'Book a free 15-minute intro call with Lysander Verschuur to see if therapy is the right step for you.',
                'og_title'         => 'Book a Free Intro Call',
                'og_description'   => 'Schedule a no-obligation 15-minute call with Lysander Verschuur — psychologist and trauma therapist.',
            ],
            [
                'page_key'         => 'faq',
                'meta_title'       => 'FAQ — Common Questions About Therapy with Lysander',
                'meta_description' => 'Answers to frequently asked questions about starting therapy, what to expect, session formats, and more.',
                'og_title'         => 'Frequently Asked Questions',
                'og_description'   => 'Everything you need to know about starting therapy with Lysander Verschuur.',
            ],
            [
                'page_key'         => 'trauma-approach',
                'meta_title'       => 'Trauma Therapy Approach — EMDR & Trauma-Focused CBT',
                'meta_description' => 'Lysander Verschuur uses evidence-based trauma therapy approaches including EMDR and trauma-focused CBT to support recovery.',
                'og_title'         => 'Trauma Therapy Approach',
                'og_description'   => 'Learn about the trauma therapy methods used by Lysander Verschuur, including EMDR and CBT.',
            ],
        ];

        foreach ($pages as $data) {
            SeoSetting::updateOrCreate(['page_key' => $data['page_key']], $data);
        }
    }
}
