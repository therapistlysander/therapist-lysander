<?php

namespace App\Console\Commands;

use App\Models\PageSection;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Console\Command;

class FixRichTextAlignment extends Command
{
    protected $signature = 'content:fix-alignment';
    protected $description = 'Strip alignment classes and normalize <br> tags in all rich-text content';

    public function handle()
    {
        $fixed = 0;

        // Fix PageSections
        foreach (PageSection::all() as $section) {
            foreach (['en', 'nl'] as $locale) {
                $content = $section->getTranslation('content', $locale);
                if (!is_array($content)) continue;

                $changed = false;
                foreach ($content as $key => $value) {
                    if (is_string($value) && (str_contains($value, 'ql-align') || str_contains($value, '<br>'))) {
                        $content[$key] = $this->cleanHtml($value);
                        $changed = true;
                    }
                }

                if ($changed) {
                    $section->setTranslation('content', $locale, $content);
                    $section->save();
                    $fixed++;
                    $this->line("  Fixed: {$section->section_key} ({$locale})");
                }
            }
        }

        // Fix FAQs
        foreach (Faq::all() as $faq) {
            foreach (['en', 'nl'] as $locale) {
                foreach (['question', 'answer'] as $field) {
                    $value = $faq->getTranslation($field, $locale);
                    if (is_string($value) && (str_contains($value, 'ql-align') || str_contains($value, '<br>'))) {
                        $faq->setTranslation($field, $locale, $this->cleanHtml($value));
                        $faq->save();
                        $fixed++;
                        $this->line("  Fixed FAQ #{$faq->id} {$field} ({$locale})");
                    }
                }
            }
        }

        // Fix Testimonials
        foreach (Testimonial::all() as $testimonial) {
            foreach (['en', 'nl'] as $locale) {
                foreach (['headline', 'body', 'quote'] as $field) {
                    $value = $testimonial->getTranslation($field, $locale);
                    if (is_string($value) && (str_contains($value, 'ql-align') || str_contains($value, '<br>'))) {
                        $testimonial->setTranslation($field, $locale, $this->cleanHtml($value));
                        $testimonial->save();
                        $fixed++;
                        $this->line("  Fixed Testimonial #{$testimonial->id} {$field} ({$locale})");
                    }
                }
            }
        }

        $this->info("Done! Fixed {$fixed} fields across all models.");
    }

    private function cleanHtml(string $html): string
    {
        // Remove alignment classes
        $html = preg_replace('/\s*class\s*=\s*"ql-align-(center|right|justify)"/i', '', $html);

        // Convert <br><br> (double breaks) to paragraph breaks
        $html = preg_replace('/(<br\s*\/?>\s*){2,}/i', '</p><p>', $html);

        // Convert remaining single <br> at start/end of content to nothing
        $html = preg_replace('/^\s*(<br\s*\/?>\s*)+/i', '', $html);
        $html = preg_replace('/\s*(<br\s*\/?>\s*)+$/i', '', $html);

        return $html;
    }
}
