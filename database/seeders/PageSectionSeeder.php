<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = array_merge(
            $this->homeSections(),
            $this->aboutSections(),
            $this->approachSections(),
            $this->trainingSections(),
            $this->testimonialsSections(),
            $this->feesSections(),
            $this->faqSections(),
            $this->contactSections(),
            $this->bookingSections(),
        );

        foreach ($sections as $data) {
            PageSection::firstOrCreate(
                ['section_key' => $data['section_key']],
                $data
            );
        }

        // Clean up old keys that have been migrated to prefixed versions
        PageSection::whereIn('section_key', ['hero', 'cta_intro', 'process', 'about_teaser', 'cta_bottom'])
            ->delete();
    }

    private function homeSections(): array
    {
        return [
            [
                'page' => 'home',
                'section_key' => 'home_hero',
                'label' => 'Hero Section',
                'sort_order' => 1,
                'content' => [
                    'heading' => 'Online therapy for adults ready to move forward.',
                    'subheading' => 'Psychologist & Trauma Therapist',
                    'body' => 'Online therapy for adults struggling with the effects of trauma and PTSD, anxiety, self-worth difficulties, emotional overwhelm, and longstanding psychological patterns. Integrative, evidence-based, and tailored to the individual.',
                    'image' => '/images/lysander-hero.jpg',
                    'cta_primary_label' => 'Book a Free 30-Minute Intro Call',
                    'cta_primary_url' => '/booking',
                    'cta_secondary_label' => 'Trauma & My Approach',
                    'cta_secondary_url' => '/trauma-approach',
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'home_intro',
                'label' => 'Introduction',
                'sort_order' => 2,
                'content' => [
                    'heading' => 'A psychologist who has walked the path himself',
                    'body' => '<p>I am a psychologist working with adults who feel emotionally overwhelmed, stuck in longstanding patterns, or disconnected from themselves and their lives.</p><p>Many of the people I work with struggle with the effects of trauma, anxiety, chronic self-criticism, emotional dysregulation, or difficulties related to self-worth and relationships.</p><p>Alongside my clinical training, my work is informed by <strong>personal experience with trauma, anxiety, and struggles with self-worth</strong>. My approach is warm, direct, collaborative, and focused on meaningful psychological change.</p>',
                    'image' => '/images/ff96a9dc8ea72c2c-11062b_aa33e58c18774e7db74c68e74a6c231e-mv2.jpg',
                    'stats' => [
                        ['value' => 'EMDR', 'label' => 'Advanced certified'],
                        ['value' => 'MSc.', 'label' => 'Psychology degree'],
                        ['value' => '10+', 'label' => 'Evidence-based methods'],
                    ],
                    'cta_primary_label' => 'Trauma & My Approach',
                    'cta_primary_url' => '/trauma-approach',
                    'cta_secondary_label' => 'Book a Free Intro Call',
                    'cta_secondary_url' => '/booking',
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'home_areas',
                'label' => 'Areas of Work',
                'sort_order' => 3,
                'content' => [
                    'heading' => 'Individualized & goal-oriented therapy',
                    'body' => '<p>Effective therapy requires more than applying a standard protocol. Each person brings a unique history, emotional world, personality structure, and set of coping patterns into therapy.</p><p>My aim is to understand the underlying processes contributing to your difficulties and to tailor treatment accordingly. Therapy is active, practical, and collaborative.</p>',
                    'image' => '/images/540a4d3e95a87201-11062b_e8771669914d4b8a949e06893dfd43a0-mv2.jpg',
                    'items' => [
                        ['title' => 'Trauma and PTSD'],
                        ['title' => 'Anxiety disorders and panic'],
                        ['title' => 'Depression and grief'],
                        ['title' => 'Self-esteem and self-worth difficulties'],
                        ['title' => 'Perfectionism and control-related patterns'],
                        ['title' => 'Emotional regulation and anger-related difficulties'],
                        ['title' => 'Burnout and chronic stress'],
                        ['title' => 'Compulsive or avoidance-based coping patterns'],
                    ],
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'home_online_strip',
                'label' => 'Online Therapy Strip',
                'sort_order' => 4,
                'content' => [
                    'body' => '"Online therapy has been consistently shown to be as effective as face-to-face therapy for anxiety, depression, trauma, and stress-related difficulties."',
                    'cta_label' => 'Book a Free Intro Call',
                    'cta_url' => '/booking',
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'home_approaches',
                'label' => 'Therapeutic Approaches',
                'sort_order' => 5,
                'content' => [
                    'heading' => 'Evidence-based approaches tailored to you',
                    'body' => 'I work integratively, drawing from multiple proven methods to address the root causes of your difficulties — not just the symptoms.',
                    'items' => [
                        ['key' => 'cbt', 'title' => 'Cognitive Behavioural Therapy (CBT)', 'description' => 'CBT helps you identify and change unhelpful thought patterns and behaviours that maintain distress. We work together to recognise negative automatic thoughts, challenge their validity, and develop healthier cognitive patterns — resulting in lasting improvements in mood, anxiety, and self-esteem.'],
                        ['key' => 'act', 'title' => 'Acceptance and Commitment Therapy (ACT)', 'description' => 'ACT shifts the focus from fighting difficult thoughts and feelings to developing psychological flexibility. By identifying your core values and committing to actions aligned with them, you can build a meaningful life even in the presence of inner pain.'],
                        ['key' => 'emdr', 'title' => 'Eye Movement Desensitisation & Reprocessing (EMDR)', 'description' => 'EMDR is one of the most evidence-based treatments for trauma and PTSD. Using bilateral stimulation, EMDR helps the brain process and integrate traumatic memories that have become "stuck."'],
                        ['key' => 'schema', 'title' => 'Schema Therapy & Parts Work', 'description' => 'Schema therapy addresses deep-rooted emotional patterns formed in childhood that drive recurring difficulties in adult life. Combined with parts work, we bring care to wounded inner parts, replacing maladaptive coping with genuine emotional healing.'],
                        ['key' => 'somatic', 'title' => 'Somatic Psychotherapy', 'description' => 'Trauma is held not just in the mind but in the body. Somatic approaches address how stress, trauma, and emotion are stored in physical tension, movement patterns, and nervous system responses.'],
                    ],
                    'cta_label' => 'View Trauma & My Approach',
                    'cta_url' => '/trauma-approach',
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'home_process',
                'label' => 'Process Steps',
                'sort_order' => 6,
                'content' => [
                    'heading' => 'Starting therapy — what to expect',
                    'steps' => [
                        ['title' => 'Free Introduction Call', 'description' => 'A free 30-minute online introduction call to briefly explore your current situation, your goals for therapy, and whether we feel like a good fit to work together.'],
                        ['title' => 'Intake Session', 'description' => 'An in-depth 60-minute intake session exploring your background, current difficulties, relevant life experiences, and treatment goals in greater detail.'],
                        ['title' => 'Treatment Plan', 'description' => 'Following the intake, a treatment plan is developed outlining the main complaints, therapeutic goals, and proposed treatment approach — tailored to you.'],
                        ['title' => 'Ongoing Sessions', 'description' => 'Follow-up sessions of 60 minutes, tailored to your individual needs. Sessions are active, collaborative, and adapted to your pace and needs.'],
                    ],
                    'cta_label' => 'View Fees & Process',
                    'cta_url' => '/fees-process',
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'home_testimonials',
                'label' => 'Testimonials Header',
                'sort_order' => 7,
                'content' => [
                    'heading' => 'What clients say',
                    'subheading' => 'Client words',
                    'cta_label' => 'Read full testimonials',
                    'cta_url' => '/testimonials',
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'home_working_together',
                'label' => 'Working Together',
                'sort_order' => 8,
                'content' => [
                    'heading' => 'A space that is safe, thoughtful, and collaborative',
                    'body' => '<p>Therapy is not about "fixing" who you are. Often, it involves understanding the patterns that developed in response to difficult life experiences — and gradually creating more freedom, flexibility, and self-trust in the present.</p><p>My role is to provide a space that is safe, thoughtful, collaborative, and focused on real psychological change.</p>',
                    'image' => '/images/1cea4c553e34803a-a3c153_bbf1019446e34069a3b96c18f172e810-mv2.jpg',
                    'cta_label' => 'Schedule a Free Introduction Call',
                    'cta_url' => '/booking',
                ],
            ],
            [
                'page' => 'home',
                'section_key' => 'home_cta_bottom',
                'label' => 'Bottom CTA',
                'sort_order' => 9,
                'content' => [
                    'heading' => 'Take the first step toward change',
                    'body' => "Whether you're struggling with trauma, anxiety, depression, or simply feeling stuck — I'm here. The first conversation is free and without commitment.",
                    'cta_primary_label' => 'Book a Free 30-Minute Intro Call',
                    'cta_primary_url' => '/booking',
                    'cta_secondary_label' => 'WhatsApp me',
                    'cta_secondary_url' => 'https://wa.me/66935309052?text=Hi%20Lysander%2C%20I%27d%20like%20to%20learn%20more%20about%20therapy.',
                ],
            ],
        ];
    }

    private function aboutSections(): array
    {
        return [
            [
                'page' => 'about',
                'section_key' => 'about_hero',
                'label' => 'Hero',
                'sort_order' => 1,
                'content' => [
                    'heading' => 'Psychologist & Trauma Specialist',
                    'subheading' => 'About Lysander',
                    'body' => 'A compassionate, pragmatic approach — drawing from lived experience, evidence-based methods, and genuine care for every person I work with.',
                ],
            ],
            [
                'page' => 'about',
                'section_key' => 'about_who',
                'label' => 'Who I Am',
                'sort_order' => 2,
                'content' => [
                    'heading' => 'Lysander Verschuur, MSc.',
                    'body' => '<p>I am a trained psychologist working with individuals experiencing <strong>psychological and emotional difficulties such as trauma, anxiety, depression, and self-esteem issues</strong>. I am here to support people through some of life\'s hardest chapters.</p><p>My work is focused on the <strong>treatment and reduction of mental health complaints of individual clients</strong>, using evidence-based therapeutic methods. I work with both Dutch-speaking and English-speaking clients.</p><p>I help clients move from states of <strong>overwhelm, constriction, and emotional distress</strong> toward <strong>greater stability, clarity, and psychological flexibility</strong>.</p>',
                    'image' => '/images/24946176bc4178fd-d0220c_d40feae8ad4e4961b519d527fe1eb369-mv2_d_1440_1920_s_2.jpg',
                    'cta_primary_label' => 'Book a session',
                    'cta_primary_url' => '/booking',
                    'cta_secondary_label' => 'WhatsApp',
                    'cta_secondary_url' => 'https://wa.me/66935309052',
                ],
            ],
            [
                'page' => 'about',
                'section_key' => 'about_personal',
                'label' => 'Personal Journey',
                'sort_order' => 3,
                'content' => [
                    'heading' => 'A therapist who has been there',
                    'body' => '<p>For years, I struggled with <strong>trauma, anxiety, and a harsh inner critic</strong>. I know firsthand how deeply these patterns can affect your life — the way they shape your relationships, your sense of self, and your ability to feel at home in the world. This personal experience informs my work — not as a replacement for clinical methods, but as something that <strong>deepens empathy, understanding, and precision in therapy</strong>.</p><p>Everything I offer in therapy — from EMDR and ACT to mindfulness and values-based living — I have lived myself. I have practised it, wrestled with it, and integrated it. This personal experience shapes how I work: <strong>compassionate, pragmatic, non-judgmental, and fully committed to your growth</strong>.</p><p>Therapy with me is not just about symptom relief — it is about <strong>finding your way back to yourself</strong>, grounded in self-understanding, self-worth, and personal empowerment.</p>',
                ],
            ],
            [
                'page' => 'about',
                'section_key' => 'about_how_i_work',
                'label' => 'How I Work',
                'sort_order' => 4,
                'content' => [
                    'heading' => 'Integrative & evidence-based',
                    'body' => 'I work integratively, drawing from evidence-based approaches that each serve a distinct therapeutic purpose.',
                    'image' => '/images/8d05ae73f3a7dbe5-11062b_a417184e892349f89eb10b97fd3d5a91-mv2.jpg',
                    'items' => [
                        ['title' => 'Cognitive Behavioural Therapy (CBT)'],
                        ['title' => 'Acceptance & Commitment Therapy (ACT)'],
                        ['title' => 'EMDR'],
                        ['title' => 'Schema Therapy'],
                        ['title' => 'Somatic Psychotherapy'],
                        ['title' => 'Exposure Therapy'],
                        ['title' => 'Flash Technique'],
                        ['title' => 'Imagery Rescripting'],
                        ['title' => 'Parts Work'],
                        ['title' => 'Mindfulness-based approaches'],
                    ],
                ],
            ],
            [
                'page' => 'about',
                'section_key' => 'about_values',
                'label' => 'Approach Values',
                'sort_order' => 5,
                'content' => [
                    'heading' => 'Tailored, collaborative, direct',
                    'body' => '<p><strong>Every person carries a unique life story, emotional landscape, and psychological makeup.</strong> I tailor each therapy trajectory to fit the person in front of me.</p><p><strong>My approach is practical, goal-oriented, and collaborative</strong>: we work together to develop insight, emotional resilience, and concrete tools for change.</p>',
                    'cards' => [
                        ['title' => 'Compassionate', 'description' => 'Non-judgmental, warm, and genuinely caring in every session.'],
                        ['title' => 'Pragmatic', 'description' => 'No endless circles. We move toward meaningful change, session by session.'],
                        ['title' => 'Empowering', 'description' => 'My goal is your independence — the tools to thrive on your own.'],
                        ['title' => 'Evidence-based', 'description' => 'Every method I use is grounded in clinical research and proven effectiveness.'],
                    ],
                ],
            ],
            [
                'page' => 'about',
                'section_key' => 'about_cta',
                'label' => 'Bottom CTA',
                'sort_order' => 6,
                'content' => [
                    'heading' => 'Ready to take the first step?',
                    'body' => 'A short message is all it takes to start. The first intake conversation is free and without obligation.',
                    'cta_label' => 'Book a session',
                    'cta_url' => '/booking',
                ],
            ],
        ];
    }

    private function approachSections(): array
    {
        return [
            [
                'page' => 'approach',
                'section_key' => 'approach_hero',
                'label' => 'Hero',
                'sort_order' => 1,
                'content' => [
                    'heading' => 'Trauma & My Approach',
                    'subheading' => 'Trauma & My Approach',
                    'body' => 'Trauma is not only about what happened in the past, but also about the ways those experiences continue to affect the present.',
                ],
            ],
            [
                'page' => 'approach',
                'section_key' => 'approach_understanding',
                'label' => 'Understanding Trauma',
                'sort_order' => 2,
                'content' => [
                    'heading' => 'How trauma affects the present',
                    'body' => '<p>Difficult or overwhelming experiences can leave a lasting impact on the nervous system, emotional regulation, relationships, and sense of self. Trauma may present through symptoms such as anxiety, panic, emotional numbness, hypervigilance, intrusive memories, flashbacks, shame, low self-worth, or persistent patterns of avoidance and control.</p><p>Sometimes the source of trauma is clear and identifiable. In other cases, the impact develops more gradually through repeated experiences of criticism, emotional neglect, instability, rejection, or chronic stress.</p><p>I work with both acute trauma <em>("big T" trauma)</em> and more cumulative or relational forms of trauma <em>("small t" trauma)</em>. Both can have a profound psychological impact, and both are treatable.</p>',
                    'image' => '/images/de8d235e4bd94eb8-a3c153_20122b9a32cc4e9a9faca835b9f82d14-mv2.jpg',
                    'cta_label' => 'Book a Free 30-Minute Intro Call',
                    'cta_url' => '/booking',
                ],
            ],
            [
                'page' => 'approach',
                'section_key' => 'approach_types',
                'label' => 'Types of Trauma',
                'sort_order' => 3,
                'content' => [
                    'heading' => 'Trauma I work with',
                    'body' => 'Regardless of how trauma manifests itself, therapy can help restore a greater sense of safety, stability, emotional freedom, and self-trust.',
                    'items' => [
                        ['title' => 'War zone experiences'],
                        ['title' => 'Accidents and injury-related trauma'],
                        ['title' => 'Sexual abuse and assault'],
                        ['title' => 'Medical trauma'],
                        ['title' => 'Panic attacks and overwhelming psychological experiences'],
                        ['title' => 'Childhood abuse and emotional neglect'],
                        ['title' => 'Grief and traumatic loss'],
                        ['title' => 'Bullying and social exclusion'],
                        ['title' => 'High-conflict relational or family situations'],
                        ['title' => 'Trauma-related self-worth and identity difficulties'],
                    ],
                ],
            ],
            [
                'page' => 'approach',
                'section_key' => 'approach_treatments',
                'label' => 'Treatment Methods',
                'sort_order' => 4,
                'content' => [
                    'heading' => 'Trauma-focused treatment',
                    'body' => 'My work integrates evidence-based trauma-focused approaches, tailored to the individual and the nature of the difficulties involved. The aim is not only symptom reduction, but also helping clients process unresolved experiences and develop a more stable and compassionate relationship with themselves.',
                    'cards' => [
                        ['subtitle' => 'Primary method', 'title' => 'EMDR', 'description' => 'Eye Movement Desensitization and Reprocessing — the gold standard evidence-based treatment for trauma and PTSD. Using bilateral stimulation to help the brain process and integrate traumatic memories.'],
                        ['subtitle' => 'Trauma processing', 'title' => 'Exposure Therapy', 'description' => 'Gradual, structured confrontation with fear and trauma. Helps reduce avoidance and integrate difficult experiences into a coherent understanding of the self.'],
                        ['subtitle' => 'Trauma processing', 'title' => 'Flash Technique', 'description' => 'A gentler entry point for trauma processing when memories are highly distressing or flooding. Particularly helpful when clients are in acute distress or when dissociation is present.'],
                        ['subtitle' => 'Memory reworking', 'title' => 'Imagery Rescripting', 'description' => 'Rewriting painful emotional memories to reduce their distress and emotional charge. Particularly effective for childhood trauma, neglect, and shame-based experiences.'],
                        ['subtitle' => 'Deep patterns', 'title' => 'Schema Therapy', 'description' => 'Addressing deep-rooted emotional patterns formed in childhood. Brings care and understanding to wounded inner parts, replacing maladaptive coping with genuine emotional healing.'],
                        ['subtitle' => 'Integrative', 'title' => 'Parts-oriented & Somatic', 'description' => 'Body-based and parts-oriented approaches to access and release what is held in the body and nervous system — where talk therapy alone cannot reach.'],
                    ],
                ],
            ],
            [
                'page' => 'approach',
                'section_key' => 'approach_emdr',
                'label' => 'About EMDR',
                'sort_order' => 5,
                'content' => [
                    'heading' => 'EMDR is not only about the past',
                    'body' => '<p>EMDR is widely known as a treatment for painful memories and trauma from the past. However, it can also be highly effective in treating intense fears, catastrophic future scenarios, and intrusive "flashforward" images that keep people stuck in anxiety and avoidance.</p><p>When these fears become emotionally charged and repetitive, they can strongly shape a person\'s daily life and sense of freedom. Through EMDR, exposure therapy, CBT, and experiential interventions, these patterns are often very treatable.</p>',
                    'image' => '/images/4e854682cd76d19d-30f861_eb190602eba243f586aac2f6026db98b-mv2.jpg',
                    'cards' => [
                        ['title' => 'Panic about panic attacks', 'description' => 'Treating the fear of anxiety itself — not just the symptoms.'],
                        ['title' => 'Health anxiety', 'description' => 'Catastrophic health fears and intrusive bodily preoccupations.'],
                        ['title' => 'Fear of losing control', 'description' => 'Social fears, shame spirals, and catastrophic "what if" thinking.'],
                        ['title' => 'Future-oriented anxiety', 'description' => 'Emotionally charged flashforwards that keep people stuck.'],
                    ],
                ],
            ],
            [
                'page' => 'approach',
                'section_key' => 'approach_why',
                'label' => 'Why I Specialize',
                'sort_order' => 6,
                'content' => [
                    'heading' => 'Why I specialize in trauma treatment',
                    'body' => '<p>I have a strong affinity for trauma-focused work because trauma often lies at the core of longstanding psychological suffering. When unresolved experiences begin to process and integrate, meaningful shifts can occur — not only in symptoms, but also in the way people relate to themselves, others, and life more broadly.</p><p>One of the reasons I value trauma-focused therapy is that treatment can often be both structured and effective. Over the years, I have repeatedly seen how reducing traumatic stress can create space for greater emotional freedom, self-understanding, resilience, and connection.</p>',
                    'quote' => 'Trauma therapy is ultimately about helping people move from survival-based patterns toward a greater sense of safety, flexibility, and trust — both in themselves and in life.',
                    'image' => '/images/24946176bc4178fd-d0220c_d40feae8ad4e4961b519d527fe1eb369-mv2_d_1440_1920_s_2.jpg',
                    'cta_primary_label' => 'View Clinical Training',
                    'cta_primary_url' => '/clinical-training',
                    'cta_secondary_label' => 'Book a Free Intro Call',
                    'cta_secondary_url' => '/booking',
                ],
            ],
            [
                'page' => 'approach',
                'section_key' => 'approach_cta',
                'label' => 'Bottom CTA',
                'sort_order' => 7,
                'content' => [
                    'heading' => 'Meaningful recovery is possible',
                    'body' => 'Trauma can deeply affect the way a person experiences themselves, others, and the world around them. At the same time, meaningful recovery and psychological change are possible. Therapy offers the possibility to process unresolved experiences, reduce the grip of fear and avoidance, and create more space for emotional freedom and stability.',
                    'cta_primary_label' => 'Book a Free 30-Minute Intro Call',
                    'cta_primary_url' => '/booking',
                    'cta_secondary_label' => 'WhatsApp me',
                    'cta_secondary_url' => 'https://wa.me/66935309052?text=Hi%20Lysander%2C%20I%27d%20like%20to%20learn%20more%20about%20therapy.',
                ],
            ],
        ];
    }

    private function trainingSections(): array
    {
        return [
            [
                'page' => 'training',
                'section_key' => 'training_hero',
                'label' => 'Hero',
                'sort_order' => 1,
                'content' => [
                    'heading' => 'Clinical Training & Continued Education',
                    'subheading' => 'Professional background',
                    'body' => 'Advanced clinical training in multiple evidence-based psychotherapy approaches, with a particular focus on trauma treatment, experiential therapies, and integrative psychotherapy.',
                ],
            ],
            [
                'page' => 'training',
                'section_key' => 'training_background',
                'label' => 'Academic Background',
                'sort_order' => 2,
                'content' => [
                    'heading' => 'MSc. Psychology',
                    'body' => '<p>I hold an <strong>MSc in Psychology</strong>, with additional academic specialization in <strong>Social Psychology</strong> and <strong>Neurocognitive Science</strong>.</p><p>I have completed advanced clinical training in multiple evidence-based psychotherapy approaches, with a particular focus on trauma treatment, experiential therapies, and integrative psychotherapy.</p><p>I view continued professional development as an essential part of providing thoughtful, up-to-date, and effective psychological care.</p>',
                    'image' => '/images/ff96a9dc8ea72c2c-11062b_aa33e58c18774e7db74c68e74a6c231e-mv2.jpg',
                    'stats' => [
                        ['value' => 'MSc.', 'label' => 'Psychology'],
                        ['value' => 'EMDR', 'label' => 'Advanced certified'],
                        ['value' => '10+', 'label' => 'Training programmes'],
                    ],
                ],
            ],
            [
                'page' => 'training',
                'section_key' => 'training_categories',
                'label' => 'Training List',
                'sort_order' => 3,
                'content' => [
                    'heading' => 'Specialized clinical training',
                    'groups' => [
                        [
                            'title' => 'Trauma & EMDR',
                            'items' => [
                                ['title' => 'EMDR Foundation Training'],
                                ['title' => 'EMDR Mastertraining'],
                                ['title' => 'Affect-Focused EMDR'],
                                ['title' => 'Exposure Therapy for EMDR Therapists'],
                                ['title' => 'Flash Technique 2.0'],
                                ['title' => 'Anger, Rage & Revenge Protocol'],
                                ['title' => 'Imagery Rescripting'],
                            ],
                        ],
                        [
                            'title' => 'Schema Therapy & Experiential',
                            'items' => [
                                ['title' => 'Fundamentals of Schema Therapy'],
                                ['title' => 'ACT & Schema Therapy Integration'],
                                ['title' => 'EMDR & Schema Therapy Integration'],
                                ['title' => 'Boxing-Based Psychotherapy'],
                            ],
                        ],
                        [
                            'title' => 'ACT & Cognitive Behavioural Therapy',
                            'items' => [
                                ['title' => 'Fundamentals of ACT'],
                                ['title' => 'ACT Follow-Up Training'],
                                ['title' => 'ACT in Groups'],
                                ['title' => 'Cognitive Behavioral Therapy (CBT)'],
                                ['title' => 'Beck Institute CBT Training'],
                            ],
                        ],
                        [
                            'title' => 'Professional Background',
                            'items' => [
                                ['title' => 'MSc. in Psychology'],
                                ['title' => 'Academic specialization: Social Psychology'],
                                ['title' => 'Academic specialization: Neurocognitive Science'],
                                ['title' => 'International clinical experience across diverse populations'],
                                ['title' => 'Broad range of psychological difficulties and treatment needs'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'page' => 'training',
                'section_key' => 'training_approach',
                'label' => 'Approach',
                'sort_order' => 4,
                'content' => [
                    'heading' => 'Integrative, trauma-informed, individualized',
                    'body' => '<p>Alongside my clinical work, I have experience working with an international client population and with a broad range of psychological difficulties and treatment needs.</p><p>My work combines evidence-based practice with an integrative, trauma-informed, experiential, and individualized approach to psychological treatment. I view continued professional development as an essential part of providing thoughtful, up-to-date, and effective psychological care.</p>',
                    'cta_primary_label' => 'View Trauma & My Approach',
                    'cta_primary_url' => '/trauma-approach',
                    'cta_secondary_label' => 'Book a Free Intro Call',
                    'cta_secondary_url' => '/booking',
                ],
            ],
        ];
    }

    private function testimonialsSections(): array
    {
        return [
            [
                'page' => 'testimonials',
                'section_key' => 'testimonials_hero',
                'label' => 'Hero',
                'sort_order' => 1,
                'content' => [
                    'heading' => 'What clients say',
                    'subheading' => 'Client experiences',
                    'body' => 'Real words from real people. These testimonials reflect the lived experience of clients who chose to share their journey through therapy with Lysander.',
                ],
            ],
            [
                'page' => 'testimonials',
                'section_key' => 'testimonials_quote',
                'label' => 'Featured Quote',
                'sort_order' => 2,
                'content' => [
                    'body' => '"If he can help me, he can help you."',
                    'attribution' => '— Rut',
                ],
            ],
            [
                'page' => 'testimonials',
                'section_key' => 'testimonials_grid',
                'label' => 'Grid Header',
                'sort_order' => 3,
                'content' => [
                    'heading' => 'Stories of recovery & growth',
                    'subheading' => "Each person's experience is unique. These testimonials are shared with the permission of the clients and represent genuine experiences from therapy.",
                ],
            ],
            [
                'page' => 'testimonials',
                'section_key' => 'testimonials_cta',
                'label' => 'Bottom CTA',
                'sort_order' => 4,
                'content' => [
                    'heading' => 'Begin your own journey',
                    'body' => 'Every story of recovery starts with a single step. Reach out and let\'s talk about what brings you here. The first conversation is free and without commitment.',
                    'cta_primary_label' => 'Book a Free 30-Minute Intro Call',
                    'cta_primary_url' => '/booking',
                    'cta_secondary_label' => 'WhatsApp me',
                    'cta_secondary_url' => 'https://wa.me/66935309052?text=Hi%20Lysander%2C%20I%27d%20like%20to%20learn%20more%20about%20therapy.',
                ],
            ],
        ];
    }

    private function feesSections(): array
    {
        return [
            [
                'page' => 'fees',
                'section_key' => 'fees_hero',
                'label' => 'Hero',
                'sort_order' => 1,
                'content' => [
                    'heading' => 'Fees & Practical Information',
                    'subheading' => 'Practical information',
                    'body' => 'Transparent information about session fees, what is included, and how therapy begins. Starting is always free and without commitment.',
                ],
            ],
            [
                'page' => 'fees',
                'section_key' => 'fees_pricing',
                'label' => 'Session Fees',
                'sort_order' => 2,
                'content' => [
                    'heading' => 'Clear, transparent pricing',
                    'body' => '<p>Individual therapy sessions are <strong>60 minutes</strong> and cost <strong>€110 per session</strong>.</p><p>I currently maintain a limited caseload to provide thoughtful and attentive care. Waiting times are typically around <strong>2–4 weeks</strong>.</p>',
                    'fee_amount' => '€110',
                    'fee_duration' => 'Per session · 60 minutes',
                    'items' => [
                        ['title' => 'Reflection or e-health documents after sessions'],
                        ['title' => 'Exercises or therapeutic material between sessions'],
                        ['title' => 'Preparation and integration of therapeutic work'],
                        ['title' => 'Limited contact between sessions for practical questions'],
                    ],
                    'cta_label' => 'Book a Free Intro Call',
                    'cta_url' => '/booking',
                ],
            ],
            [
                'page' => 'fees',
                'section_key' => 'fees_process',
                'label' => 'Therapy Process',
                'sort_order' => 3,
                'content' => [
                    'heading' => 'What to Expect',
                    'subheading' => 'Therapy begins with a free introductory call, followed by an intake session where we explore your situation, goals, and what you hope to gain from therapy. From there, treatment is tailored to your individual needs.',
                    'steps' => [
                        ['title' => 'Free Introductory Call', 'description' => 'We briefly discuss what brings you to therapy, your goals, and whether we feel like a good fit to work together.', 'duration' => '30 minutes · Free', 'badge' => 'Free'],
                        ['title' => 'Intake Session', 'description' => "An in-depth session exploring your background, current difficulties, relevant life experiences, and treatment goals. Prior to the session, you'll complete a questionnaire that helps guide the assessment process. Following the intake, you'll receive a personalized treatment plan outlining the main difficulties, therapeutic goals, and proposed treatment approach.", 'duration' => '60 minutes', 'badge' => null],
                        ['title' => 'Ongoing Sessions', 'description' => 'Sessions tailored to your individual needs, goals, and pace. Together we work toward meaningful and lasting psychological change.', 'duration' => '60 minutes', 'badge' => null],
                    ],
                ],
            ],
            [
                'page' => 'fees',
                'section_key' => 'fees_info',
                'label' => 'Session Info',
                'sort_order' => 4,
                'content' => [
                    'heading' => 'Session information',
                    'cards' => [
                        ['title' => 'Online sessions', 'description' => 'Sessions take place in a secure, confidential online setting. Available to clients worldwide. Online therapy is as effective as in-person for most psychological difficulties.'],
                        ['title' => 'In-person (Amsterdam)', 'description' => 'Primarily an online practice. A limited number of in-person sessions in Amsterdam are available on request — please reach out to discuss possibilities.'],
                        ['title' => 'Session duration', 'description' => 'Sessions are 60 minutes. The free introduction call is 30 minutes. Sessions are typically weekly in the early phase of therapy.'],
                        ['title' => 'Languages', 'description' => 'Sessions are conducted in Dutch or English. Both languages are equally available for all therapy modalities.'],
                    ],
                ],
            ],
            [
                'page' => 'fees',
                'section_key' => 'fees_cta',
                'label' => 'Bottom CTA',
                'sort_order' => 5,
                'content' => [
                    'heading' => 'The first conversation is free',
                    'subheading' => 'Schedule a free 30-minute introduction call. No commitment required.',
                    'cta_label' => 'Book a Free 30-Minute Intro Call',
                    'cta_url' => '/booking',
                ],
            ],
        ];
    }

    private function faqSections(): array
    {
        return [
            [
                'page' => 'faq',
                'section_key' => 'faq_hero',
                'label' => 'Hero',
                'sort_order' => 1,
                'content' => [
                    'heading' => 'Frequently Asked Questions',
                    'subheading' => 'Questions & Answers',
                    'body' => "Answers to the most common questions about therapy, EMDR, fees, and how to get started. If something isn't covered here, feel free to reach out directly.",
                ],
            ],
            [
                'page' => 'faq',
                'section_key' => 'faq_cta',
                'label' => 'Bottom CTA',
                'sort_order' => 3,
                'content' => [
                    'heading' => 'Still have questions?',
                    'body' => "Feel free to reach out directly — I'm happy to answer any questions before you decide to book.",
                    'cta_label' => 'Book a Free Intro Call',
                    'cta_url' => '/booking',
                ],
            ],
            [
                'page' => 'faq',
                'section_key' => 'faq_categories',
                'label' => 'FAQ Categories',
                'sort_order' => 2,
                'content' => [
                    'categories' => [
                        ['key' => 'therapy_emdr', 'label' => 'Therapy & EMDR'],
                        ['key' => 'starting_therapy', 'label' => 'Starting Therapy'],
                        ['key' => 'practical', 'label' => 'Practical Information'],
                        ['key' => 'sessions_progress', 'label' => 'Sessions & Progress'],
                    ],
                ],
            ],
        ];
    }

    private function contactSections(): array
    {
        return [
            [
                'page' => 'contact',
                'section_key' => 'contact_hero',
                'label' => 'Hero',
                'sort_order' => 1,
                'content' => [
                    'heading' => 'Contact',
                    'subheading' => 'Get in touch',
                    'body' => 'If you are considering therapy, you are welcome to schedule a free 30-minute introduction call — no commitment required.',
                ],
            ],
            [
                'page' => 'contact',
                'section_key' => 'contact_info',
                'label' => 'Contact Details',
                'sort_order' => 2,
                'content' => [
                    'heading' => "Let's talk",
                    'whatsapp_number' => '66935309052',
                    'whatsapp_text' => 'Prefer a quick message?',
                    'email' => 'therapistlysander@gmail.com',
                    'items' => [
                        ['label' => 'Email', 'value' => 'therapistlysander@gmail.com'],
                        ['label' => 'Online sessions', 'value' => 'Available worldwide via secure video call'],
                        ['label' => 'In-person sessions', 'value' => 'Amsterdam — limited availability, on request'],
                        ['label' => 'Session duration', 'value' => '60 minutes · Free introduction call (30 min)'],
                        ['label' => 'Languages', 'value' => 'Dutch & English'],
                    ],
                ],
            ],
            [
                'page' => 'contact',
                'section_key' => 'contact_booking',
                'label' => 'Booking CTA',
                'sort_order' => 3,
                'content' => [
                    'heading' => 'Book a Free 30-Minute Intro Call',
                    'body' => "Schedule a no-commitment introduction to see if we're a good fit. Primarily online, with limited in-person availability in Amsterdam.",
                    'cta_label' => 'Start booking',
                    'cta_url' => '/booking',
                ],
            ],
        ];
    }

    private function bookingSections(): array
    {
        return [
            [
                'page' => 'booking',
                'section_key' => 'booking_hero',
                'label' => 'Hero',
                'sort_order' => 1,
                'content' => [
                    'heading' => "Let's get started",
                    'subheading' => 'Book a free 30-minute introduction call. No commitment required.',
                ],
            ],
        ];
    }
}
