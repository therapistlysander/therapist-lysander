<?php

use App\Models\PageSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Update approach page section images in the database.
     */
    public function up(): void
    {
        $imageMap = [
            'approach_emdr' => '/images/ff96a9dc8ea72c2c-11062b_aa33e58c18774e7db74c68e74a6c231e-mv2.jpg',
            'approach_why'  => '/images/8d05ae73f3a7dbe5-11062b_a417184e892349f89eb10b97fd3d5a91-mv2.jpg',
        ];

        foreach ($imageMap as $sectionKey => $newImage) {
            $section = PageSection::where('section_key', $sectionKey)->first();
            if (!$section) {
                continue;
            }

            foreach (['en', 'nl'] as $locale) {
                $content = $section->getTranslation('content', $locale);
                if (is_array($content) && isset($content['image'])) {
                    $content['image'] = $newImage;
                    $section->setTranslation('content', $locale, $content);
                }
            }

            $section->save();
        }
    }

    /**
     * Reverse: restore original images.
     */
    public function down(): void
    {
        $imageMap = [
            'approach_emdr' => '/images/4e854682cd76d19d-30f861_eb190602eba243f586aac2f6026db98b-mv2.jpg',
            'approach_why'  => '/images/24946176bc4178fd-d0220c_d40feae8ad4e4961b519d527fe1eb369-mv2_d_1440_1920_s_2.jpg',
        ];

        foreach ($imageMap as $sectionKey => $originalImage) {
            $section = PageSection::where('section_key', $sectionKey)->first();
            if (!$section) {
                continue;
            }

            foreach (['en', 'nl'] as $locale) {
                $content = $section->getTranslation('content', $locale);
                if (is_array($content) && isset($content['image'])) {
                    $content['image'] = $originalImage;
                    $section->setTranslation('content', $locale, $content);
                }
            }

            $section->save();
        }
    }
};
