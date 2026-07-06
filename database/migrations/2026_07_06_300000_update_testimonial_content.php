<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Update testimonials with new client text and fix Aäron's header.
     */
    public function up(): void
    {
        $updates = [
            'Paul' => [
                'quote' => 'For the first time, I feel like I\'ve reclaimed a part of myself that was lost. After 46 years of living with the effects of trauma, EMDR with Lysander has been truly life-changing.',
                'headline' => 'For the first time, I feel like I\'ve reclaimed a part of myself that was lost.',
            ],
            'Sarah' => [
                'quote' => 'Today, I can truly say that I have found happiness and contentment. When I started therapy, I saw no light at the end of the tunnel — Lysander helped me find it again.',
                'headline' => 'Today, I can truly say that I have found happiness and contentment.',
            ],
            'Pascal' => [
                'quote' => 'After nine months, I no longer experience panic attacks or depressive symptoms. The therapy felt genuinely personal, and the insights I gained will stay with me for life.',
                'headline' => 'After nine months, I no longer experience panic attacks or depressive symptoms.',
            ],
            'Sitvanit' => [
                'quote' => 'Each session felt like a breakthrough. I let go of old patterns, gained powerful insights, and finally made changes I\'d been postponing for years.',
                'headline' => 'Each session felt like a breakthrough.',
            ],
            'Jerry' => [
                'quote' => 'The change I felt eventually became visible to others. People around me noticed that I had become more confident, grounded, and truly myself.',
                'headline' => 'The change I felt eventually became visible to others.',
            ],
            'Rut' => [
                'quote' => 'Within two months, I stopped living in the past, started focusing on solutions, and began working towards my future again. For the first time in a long time, I genuinely liked myself.',
                'headline' => 'Within two months, I stopped living in the past and started focusing on solutions.',
            ],
        ];

        foreach ($updates as $name => $data) {
            $testimonial = DB::table('testimonials')
                ->where('client_name', $name)
                ->first();

            if (!$testimonial) {
                continue;
            }

            $updateData = ['updated_at' => now()];

            // Update quote (translatable JSON)
            if (isset($data['quote'])) {
                $quoteJson = $this->mergeTranslation($testimonial->quote, $data['quote']);
                $updateData['quote'] = $quoteJson;
            }

            // Update headline (translatable JSON)
            if (isset($data['headline'])) {
                $headlineJson = $this->mergeTranslation($testimonial->headline, $data['headline']);
                $updateData['headline'] = $headlineJson;
            }

            DB::table('testimonials')
                ->where('id', $testimonial->id)
                ->update($updateData);
        }

        // Update Aäron's headline
        $aaron = DB::table('testimonials')
            ->where('client_name', 'like', '%äron%')
            ->orWhere('client_name', 'like', '%aaron%')
            ->orWhere('client_name', 'like', '%Aaron%')
            ->orWhere('client_name', 'like', '%Aäron%')
            ->first();

        if ($aaron) {
            $newHeadline = "He often picked up on things I hadn't yet put into words myself";
            $headlineJson = $this->mergeTranslation($aaron->headline, $newHeadline);

            DB::table('testimonials')
                ->where('id', $aaron->id)
                ->update([
                    'headline' => $headlineJson,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Merge a new English value into a Spatie translatable JSON field.
     * Preserves existing locale values and adds/updates 'en'.
     */
    private function mergeTranslation(?string $currentJson, string $enValue): string
    {
        if ($currentJson) {
            $decoded = json_decode($currentJson, true);
            if (is_array($decoded)) {
                $decoded['en'] = $enValue;
                return json_encode($decoded, JSON_UNESCAPED_UNICODE);
            }
        }

        // If not valid JSON or null, create new translation object
        return json_encode(['en' => $enValue], JSON_UNESCAPED_UNICODE);
    }

    public function down(): void
    {
        // No destructive changes to revert
    }
};
