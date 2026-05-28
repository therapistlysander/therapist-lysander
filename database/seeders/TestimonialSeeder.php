<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'client_name'  => 'Anonymous Client',
                'client_title' => 'EMDR Therapy',
                'quote'        => 'Working with Lysander has been transformative. For the first time I feel like I can actually process what happened to me instead of just surviving it.',
                'rating'       => 5,
                'is_featured'  => true,
                'sort_order'   => 1,
            ],
            [
                'client_name'  => 'Anonymous Client',
                'client_title' => 'Trauma-Focused CBT',
                'quote'        => 'I was nervous about therapy but Lysander made me feel completely at ease from the first session. He\'s professional, warm, and knows exactly how to help.',
                'rating'       => 5,
                'is_featured'  => true,
                'sort_order'   => 2,
            ],
            [
                'client_name'  => 'Anonymous Client',
                'client_title' => 'Anxiety & Burnout',
                'quote'        => 'After months of feeling stuck, I finally have real tools to manage my anxiety. The sessions are challenging but always feel safe.',
                'rating'       => 5,
                'is_featured'  => true,
                'sort_order'   => 3,
            ],
            [
                'client_name'  => 'Anonymous Client',
                'client_title' => 'Online Therapy',
                'quote'        => 'The online format works really well. Lysander is just as present and engaged through video as in person. Highly recommend.',
                'rating'       => 5,
                'is_featured'  => false,
                'sort_order'   => 4,
            ],
        ];

        foreach ($testimonials as $i => $data) {
            Testimonial::updateOrCreate(
                ['client_title' => $data['client_title'], 'sort_order' => $data['sort_order']],
                $data
            );
        }
    }
}
