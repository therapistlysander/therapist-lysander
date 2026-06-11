<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        // Remove all existing FAQs and reseed
        Faq::truncate();

        $faqs = [
            // ── Therapy & EMDR ──────────────────────────────────────────────
            [
                'category'   => 'therapy_emdr',
                'sort_order' => 1,
                'is_active'  => true,
                'question'   => 'What therapeutic approaches do you use?',
                'answer'     => "I work integratively, drawing from evidence-based approaches including EMDR, CBT, ACT, Schema Therapy, and selected experiential and somatic interventions. Treatment is tailored to the individual rather than restricted to a single therapeutic model.",
            ],
            [
                'category'   => 'therapy_emdr',
                'sort_order' => 2,
                'is_active'  => true,
                'question'   => 'What is EMDR?',
                'answer'     => '<p>EMDR (Eye Movement Desensitisation and Reprocessing) is an evidence-based treatment originally developed for trauma and PTSD. It helps the brain process and integrate distressing experiences that continue to trigger emotional reactions in the present.</p>'
                    . '<p>In addition to traumatic memories from the past, EMDR can also be effective for emotionally charged future-oriented fears (&ldquo;flashforwards&rdquo;), health anxiety, panic-related fears, and other anxiety-based difficulties.</p>'
                    . '<p>The goal is not to erase memories, but to reduce their emotional impact and help people respond with greater flexibility and freedom.</p>'
                    . '<p>If you&rsquo;d like to learn more about how EMDR works, watch the video below: <a href="https://www.youtube.com/watch?v=hKrfH43srg8" target="_blank" rel="noopener">https://www.youtube.com/watch?v=hKrfH43srg8</a></p>',
            ],
            [
                'category'   => 'therapy_emdr',
                'sort_order' => 3,
                'is_active'  => true,
                'question'   => 'What issues do you commonly work with?',
                'answer'     => "I commonly work with trauma and PTSD, anxiety disorders, panic, depression, grief, self-esteem difficulties, perfectionism, emotional regulation difficulties, burnout, relationship-related difficulties, and long-standing patterns rooted in earlier life experiences.",
            ],
            [
                'category'   => 'therapy_emdr',
                'sort_order' => 4,
                'is_active'  => true,
                'question'   => 'Is online therapy effective?',
                'answer'     => '<p>Research over the past decade has consistently shown that online psychotherapy can be as effective as face-to-face therapy for many psychological difficulties, including anxiety, depression, trauma-related symptoms, and stress-related disorders.</p>'
                    . '<p>Many clients appreciate the flexibility, privacy, and convenience that online therapy provides.</p>',
            ],

            // ── Starting Therapy ─────────────────────────────────────────────
            [
                'category'   => 'starting_therapy',
                'sort_order' => 1,
                'is_active'  => true,
                'question'   => 'What is psychotherapy?',
                'answer'     => '<p>Psychotherapy is a collaborative process aimed at understanding and changing patterns that contribute to emotional suffering.</p>'
                    . '<p>Depending on your needs, therapy may involve processing difficult experiences, developing new skills, strengthening self-understanding, improving relationships, and building greater psychological flexibility.</p>'
                    . '<p>The overall goal is meaningful and lasting change.</p>',
            ],
            [
                'category'   => 'starting_therapy',
                'sort_order' => 2,
                'is_active'  => true,
                'question'   => 'How do I know if therapy is right for me?',
                'answer'     => '<p>If you are experiencing emotional distress, feeling stuck, struggling with recurring patterns, or finding it difficult to manage challenges on your own, therapy may be helpful.</p>'
                    . '<p>The introductory call is a good opportunity to explore whether working together feels like the right fit.</p>',
            ],
            [
                'category'   => 'starting_therapy',
                'sort_order' => 3,
                'is_active'  => true,
                'question'   => 'What happens during the introductory call?',
                'answer'     => '<p>The introductory call is an opportunity to discuss your current difficulties, what you are looking for in therapy, and whether I am the right therapist for your needs.</p>'
                    . '<p>It is also a chance to ask questions about the therapeutic process, practical matters, and my approach to treatment.</p>',
            ],
            [
                'category'   => 'starting_therapy',
                'sort_order' => 4,
                'is_active'  => true,
                'question'   => 'What is the pre-intake questionnaire?',
                'answer'     => '<p>Before the intake session, you will be asked to complete a short questionnaire covering your background, current difficulties, relevant history, and treatment goals.</p>'
                    . '<p>This helps me prepare for the intake and allows us to use our first session more effectively.</p>',
            ],
            [
                'category'   => 'starting_therapy',
                'sort_order' => 5,
                'is_active'  => true,
                'question'   => 'Do I need a diagnosis to start therapy?',
                'answer'     => '<p>No.</p>'
                    . '<p>Many people seek therapy because they are struggling with anxiety, self-criticism, relationship difficulties, trauma-related symptoms, or feeling stuck in life.</p>'
                    . '<p>A formal diagnosis is not required to begin working together.</p>',
            ],
            [
                'category'   => 'starting_therapy',
                'sort_order' => 6,
                'is_active'  => true,
                'question'   => 'What happens after the intake session?',
                'answer'     => '<p>Following the intake, I develop a written treatment plan outlining the main difficulties, treatment goals, and proposed therapeutic approach.</p>'
                    . '<p>This provides a shared roadmap for therapy and helps ensure that treatment remains focused and purposeful.</p>',
            ],

            // ── Practical Information ────────────────────────────────────────
            [
                'category'   => 'practical',
                'sort_order' => 1,
                'is_active'  => true,
                'question'   => 'How much does therapy cost?',
                'answer'     => '<p>Individual therapy sessions are &euro;110 per 60-minute session.</p>'
                    . '<p>A free 30-minute introductory call is available before starting therapy.</p>',
            ],
            [
                'category'   => 'practical',
                'sort_order' => 2,
                'is_active'  => true,
                'question'   => 'Is therapy covered by insurance?',
                'answer'     => '<p>I currently work outside the Dutch health insurance system.</p>'
                    . '<p>However, reimbursement may sometimes be available through employers, international insurance providers, expat packages, personal development budgets, or workplace wellbeing programmes.</p>'
                    . '<p>I can provide invoices and supporting documentation where needed.</p>',
            ],
            [
                'category'   => 'practical',
                'sort_order' => 3,
                'is_active'  => true,
                'question'   => 'Do you offer online and in-person sessions?',
                'answer'     => '<p>My practice is primarily online.</p>'
                    . '<p>In-person sessions in Amsterdam may be available on a limited basis and can be discussed individually.</p>',
            ],
            [
                'category'   => 'practical',
                'sort_order' => 4,
                'is_active'  => true,
                'question'   => 'Do you work in English and Dutch?',
                'answer'     => "Yes. Therapy is available in both English and Dutch, and clients may switch between languages if that feels more natural or helpful during the therapeutic process.",
            ],
            [
                'category'   => 'practical',
                'sort_order' => 5,
                'is_active'  => true,
                'question'   => 'Do you work with international clients and expats?',
                'answer'     => "Yes. A significant proportion of my work is with international clients, expats, digital professionals, and people living abroad. Online therapy makes it possible to work together regardless of location.",
            ],
            [
                'category'   => 'practical',
                'sort_order' => 6,
                'is_active'  => true,
                'question'   => 'How long is each session?',
                'answer'     => '<p>Sessions are 60 minutes. The free introductory call is 30 minutes.</p>'
                    . '<p>Sessions are typically held weekly or every other week, though the frequency is always adapted to your individual needs, preferences, and circumstances.</p>',
            ],

            // ── Sessions & Progress ──────────────────────────────────────────
            [
                'category'   => 'sessions_progress',
                'sort_order' => 1,
                'is_active'  => true,
                'question'   => 'How many sessions will I need?',
                'answer'     => '<p>The number of sessions varies depending on your goals, the nature of the difficulties, and your individual pace.</p>'
                    . '<p>Some people benefit from short-term focused work, while others prefer longer-term therapy.</p>'
                    . '<p>My aim is generally to work as briefly as possible while allowing enough time for meaningful and lasting change.</p>'
                    . '<p>Progress is reviewed regularly throughout the process.</p>',
            ],
            [
                'category'   => 'sessions_progress',
                'sort_order' => 2,
                'is_active'  => true,
                'question'   => 'Do I need to talk about everything immediately?',
                'answer'     => '<p>No.</p>'
                    . '<p>Therapy progresses at a pace that feels manageable and appropriate for you. Building trust and understanding takes time, and there is no expectation that you disclose everything during the first sessions.</p>',
            ],
            [
                'category'   => 'sessions_progress',
                'sort_order' => 3,
                'is_active'  => true,
                'question'   => 'What if I become emotional during therapy?',
                'answer'     => '<p>Strong emotions are a normal part of therapy and often reflect meaningful therapeutic work.</p>'
                    . '<p>You are never expected to push beyond what feels manageable. Together we work at a pace that feels safe, respectful, and appropriate for your situation.</p>',
            ],
            [
                'category'   => 'sessions_progress',
                'sort_order' => 4,
                'is_active'  => true,
                'question'   => 'How quickly can I expect to notice results?',
                'answer'     => '<p>This varies from person to person and depends on the nature of the difficulties involved.</p>'
                    . '<p>Some people notice meaningful changes within a few sessions, while others benefit from a longer process. Progress is reviewed regularly, and treatment is adjusted where needed to ensure it remains focused and effective.</p>',
            ],
        ];

        foreach ($faqs as $data) {
            Faq::create($data);
        }
    }
}
