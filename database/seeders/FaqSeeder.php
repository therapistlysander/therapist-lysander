<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // Getting Started
            ['category' => 'general', 'sort_order' => 1, 'question' => 'What is psychotherapy?', 'answer' => "Psychotherapy is a collaborative process aimed at understanding and changing patterns that contribute to emotional suffering.\n\nDepending on your needs, therapy may involve processing difficult experiences, developing new skills, strengthening self-understanding, improving relationships, and building greater psychological flexibility.\n\nThe overall goal is meaningful and lasting change."],
            ['category' => 'general', 'sort_order' => 2, 'question' => 'How do I know if therapy is right for me?', 'answer' => "If you are experiencing emotional distress, feeling stuck, struggling with recurring patterns, or finding it difficult to manage challenges on your own, therapy may be helpful.\n\nThe introductory call is a good opportunity to explore whether working together feels like the right fit."],
            ['category' => 'general', 'sort_order' => 3, 'question' => 'Do you work in English and Dutch?', 'answer' => 'Yes. I offer therapy sessions in both Dutch and English, so you can choose the language you feel most comfortable expressing yourself in.'],
            ['category' => 'general', 'sort_order' => 4, 'question' => 'Do I need a diagnosis to start therapy?', 'answer' => "No.\n\nMany people seek therapy because they are struggling with anxiety, self-criticism, relationship difficulties, trauma-related symptoms, or feeling stuck in life.\n\nA formal diagnosis is not required to begin working together."],
            ['category' => 'general', 'sort_order' => 5, 'question' => 'What issues do you commonly work with?', 'answer' => 'I commonly work with trauma and PTSD, anxiety disorders, panic, depression, grief, self-esteem difficulties, perfectionism, emotional regulation difficulties, burnout, relationship-related difficulties, and long-standing patterns rooted in earlier life experiences.'],
            // Booking
            ['category' => 'booking', 'sort_order' => 1, 'question' => 'How do I book an introductory call?', 'answer' => 'You can schedule a free 30-minute introductory call directly through the online booking system. After booking, you will receive confirmation and practical information automatically.'],
            ['category' => 'booking', 'sort_order' => 2, 'question' => 'What happens during the introductory call?', 'answer' => "The introductory call is an opportunity to discuss your current difficulties, what you are looking for in therapy, and whether I am the right therapist for your needs.\n\nIt is also a chance to ask questions about the therapeutic process, practical matters, and my approach to treatment."],
            ['category' => 'booking', 'sort_order' => 3, 'question' => 'What is the pre-intake questionnaire?', 'answer' => "Before the intake session, you will be asked to complete a short questionnaire covering your background, current difficulties, relevant history, and treatment goals.\n\nThis helps me prepare for the intake and allows us to use our first session more effectively."],
            ['category' => 'booking', 'sort_order' => 4, 'question' => 'What happens after the intake session?', 'answer' => "Following the intake, I develop a written treatment plan outlining the main difficulties, treatment goals, and proposed therapeutic approach.\n\nThis provides a shared roadmap for therapy and helps ensure that treatment remains focused and purposeful."],
            // Fees & Insurance
            ['category' => 'fees', 'sort_order' => 1, 'question' => 'How much does therapy cost?', 'answer' => "Individual therapy sessions are \u20AC110 per 60-minute session.\n\nA free 30-minute introductory call is available before starting therapy."],
            ['category' => 'fees', 'sort_order' => 2, 'question' => 'Is therapy covered by insurance?', 'answer' => "I currently work outside the Dutch health insurance system.\n\nHowever, reimbursement may sometimes be available through employers, international insurance providers, expat packages, personal development budgets, or workplace wellbeing programmes.\n\nI can provide invoices and supporting documentation where needed."],
            // Sessions & Format
            ['category' => 'sessions', 'sort_order' => 1, 'question' => 'Do you offer online and in-person sessions?', 'answer' => "My practice is primarily online.\n\nIn-person sessions in Amsterdam may be available on a limited basis and can be discussed individually.\n\nResearch consistently shows that online therapy can be as effective as face-to-face therapy for many psychological difficulties."],
            ['category' => 'sessions', 'sort_order' => 2, 'question' => 'How long is each session?', 'answer' => "Standard sessions are typically 60 minutes.\n\nDepending on the situation and therapeutic goals, shorter or longer sessions can occasionally be arranged."],
            ['category' => 'sessions', 'sort_order' => 3, 'question' => 'How many sessions will I need?', 'answer' => "The number of sessions varies depending on your goals, the nature of the difficulties, and your individual pace.\n\nSome people benefit from short-term focused work, while others prefer longer-term therapy.\n\nMy aim is generally to work as briefly as possible while allowing enough time for meaningful and lasting change.\n\nProgress is reviewed regularly throughout the process."],
            ['category' => 'sessions', 'sort_order' => 4, 'question' => 'Is online therapy effective?', 'answer' => "Research over the past decade has consistently shown that online psychotherapy can be as effective as face-to-face therapy for many psychological difficulties, including anxiety, depression, trauma-related symptoms, and stress-related disorders (e.g. Luo et al., 2020; Greenwood et al., 2022).\n\nMany clients appreciate the flexibility, privacy, and convenience that online therapy provides."],
            // Therapy Approaches
            ['category' => 'approach', 'sort_order' => 1, 'question' => 'What therapeutic approaches do you use?', 'answer' => 'I work integratively, drawing from evidence-based approaches including EMDR, CBT, ACT, Schema Therapy, and selected experiential and somatic interventions. Treatment is tailored to the individual rather than restricted to a single therapeutic model.'],
            ['category' => 'approach', 'sort_order' => 2, 'question' => 'What is EMDR?', 'answer' => "EMDR (Eye Movement Desensitisation and Reprocessing) is an evidence-based treatment originally developed for trauma and PTSD. It helps the brain process and integrate distressing experiences that continue to trigger emotional reactions in the present.\n\nIn addition to traumatic memories from the past, EMDR can also be effective for emotionally charged future-oriented fears (\"flashforwards\"), health anxiety, panic-related fears, and other anxiety-based difficulties.\n\nThe goal is not to erase memories, but to reduce their emotional impact and help people respond with greater flexibility and freedom."],
        ];

        foreach ($faqs as $data) {
            Faq::updateOrCreate(
                ['question' => $data['question']],
                $data
            );
        }
    }
}
