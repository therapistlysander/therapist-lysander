<?php

use App\Models\PageSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Correct the first "Session information" card title on the Fees & Process
     * page. Its content is about online sessions, but on production the card
     * title had been changed to "Fees & Process" (the page name). This restores
     * the intended title.
     *
     * Targeted and idempotent: only touches the fees_info section, and only
     * cards currently titled exactly "Fees & Process" — so it is safe to run
     * against live data and does nothing where the title is already correct.
     */
    private array $map = [
        'en' => 'Online sessions',
        'nl' => 'Online sessies',
    ];

    public function up(): void
    {
        $this->rename('Fees & Process', fn (string $locale) => $this->map[$locale]);
    }

    public function down(): void
    {
        // Reverse the specific rename (best effort).
        $this->rename($this->map['en'], fn () => 'Fees & Process', ['en']);
        $this->rename($this->map['nl'], fn () => 'Fees & Process', ['nl']);
    }

    /**
     * Within the fees_info section, replace any card whose title equals $from
     * with the value returned by $to() for that locale.
     *
     * @param  callable(string):string  $to
     * @param  array<int, string>  $locales
     */
    private function rename(string $from, callable $to, array $locales = ['en', 'nl']): void
    {
        $section = PageSection::where('page', 'fees')
            ->where('section_key', 'fees_info')
            ->first();

        if (! $section) {
            return;
        }

        $saveNeeded = false;

        foreach ($locales as $locale) {
            $content = $section->getTranslation('content', $locale, false);

            if (empty($content['cards']) || ! is_array($content['cards'])) {
                continue;
            }

            $localeChanged = false;

            foreach ($content['cards'] as $i => $card) {
                if (($card['title'] ?? null) === $from) {
                    $content['cards'][$i]['title'] = $to($locale);
                    $localeChanged = true;
                }
            }

            if ($localeChanged) {
                $section->setTranslation('content', $locale, $content);
                $saveNeeded = true;
            }
        }

        if ($saveNeeded) {
            $section->save();
        }
    }
};
