<?php

use App\Models\UiTranslation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Replace &amp; HTML entities with literal & in UI translations.
     */
    public function up(): void
    {
        UiTranslation::where('value', 'LIKE', '%&amp;%')
            ->each(function (UiTranslation $row) {
                $row->update([
                    'value' => str_replace('&amp;', '&', $row->value),
                ]);
            });
    }

    /**
     * Reverse: restore & to &amp; (not perfectly reversible, but safe).
     */
    public function down(): void
    {
        // No-op: reversing would require knowing which & were originally &amp;
    }
};
