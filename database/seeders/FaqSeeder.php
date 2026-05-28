<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // General
            ['category' => 'general', 'sort_order' => 1, 'question' => 'What is psychotherapy?', 'answer' => 'Psychotherapy is a collaborative process between a therapist and client aimed at understanding and changing unhelpful thoughts, feelings, and behaviours. It provides a safe space to explore difficulties and develop strategies for lasting change.'],
            ['category' => 'general', 'sort_order' => 2, 'question' => 'How do I know if therapy is right for me?', 'answer' => 'If you\'re experiencing distress, feeling stuck, or struggling with something you can\'t resolve on your own, therapy may help. A free intro call is a good way to explore whether it\'s a good fit.'],
            ['category' => 'general', 'sort_order' => 3, 'question' => 'Do you work in English and Dutch?', 'answer' => 'Yes. I offer therapy sessions in both Dutch and English, so you can choose the language you feel most comfortable expressing yourself in.'],
            // Booking
            ['category' => 'booking', 'sort_order' => 1, 'question' => 'How do I book an intro call?', 'answer' => 'You can book a free 15-minute intro call through the booking form on this website. Simply fill in your details and I will get in touch to schedule a time.'],
            ['category' => 'booking', 'sort_order' => 2, 'question' => 'What happens during the intro call?', 'answer' => 'The intro call is a brief, informal conversation to see if we\'re a good fit. You can ask questions, share a bit about what you\'re looking for, and get a sense of my approach. There\'s no obligation to continue.'],
            ['category' => 'booking', 'sort_order' => 3, 'question' => 'What is the pre-intake questionnaire?', 'answer' => 'Before our first proper session, I\'ll ask you to complete a short questionnaire covering your background, what brings you to therapy, and any relevant history. This helps me prepare and make our first session more effective.'],
            // Fees
            ['category' => 'fees', 'sort_order' => 1, 'question' => 'How much do sessions cost?', 'answer' => 'Session fees are listed on the Fees & Process page. I believe in transparency around cost — please reach out if you have questions or concerns about affordability.'],
            ['category' => 'fees', 'sort_order' => 2, 'question' => 'Is therapy covered by insurance?', 'answer' => 'This depends on your insurance policy and the type of therapy. I am happy to provide receipts and documentation to support any insurance claims. Contact your insurer directly to understand your coverage.'],
            // Sessions
            ['category' => 'sessions', 'sort_order' => 1, 'question' => 'Do you offer online sessions?', 'answer' => 'Yes. I offer both in-person sessions in Amsterdam and online sessions via a secure video platform. Many clients find online therapy just as effective and more convenient.'],
            ['category' => 'sessions', 'sort_order' => 2, 'question' => 'How long is each session?', 'answer' => 'Standard sessions are 60 minutes. Intake sessions may run slightly longer to allow adequate time to explore your background and goals.'],
            ['category' => 'sessions', 'sort_order' => 3, 'question' => 'How many sessions will I need?', 'answer' => 'This varies widely depending on your goals, the nature of the difficulties, and your pace. Some people benefit from short-term focused work (10–15 sessions), while others prefer longer-term therapy. We\'ll discuss this together and review progress regularly.'],
            // Therapy approach
            ['category' => 'approach', 'sort_order' => 1, 'question' => 'What therapeutic approaches do you use?', 'answer' => 'I draw on several evidence-based approaches including EMDR (Eye Movement Desensitisation and Reprocessing), trauma-focused CBT, and schema therapy, tailored to what works best for you.'],
            ['category' => 'approach', 'sort_order' => 2, 'question' => 'What is EMDR?', 'answer' => 'EMDR is a structured therapy for trauma that uses bilateral stimulation (such as eye movements) to help the brain process and integrate traumatic memories. It is one of the most evidence-based treatments for PTSD.'],
        ];

        foreach ($faqs as $data) {
            Faq::updateOrCreate(
                ['question' => $data['question']],
                $data
            );
        }
    }
}
