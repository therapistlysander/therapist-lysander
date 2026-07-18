<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix success_desc EN — replace literal \xe2\x80\x93 with actual en-dash (U+2013)
        DB::table('ui_translations')
            ->where('locale', 'en')
            ->where('group', 'booking')
            ->where('key', 'success_desc')
            ->update([
                'value' => "Thank you, :name. Lysander will confirm your session for :datetime via email or WhatsApp within 1\xe2\x80\x932 business days.",
            ]);

        // Fix success_desc NL
        DB::table('ui_translations')
            ->where('locale', 'nl')
            ->where('group', 'booking')
            ->where('key', 'success_desc')
            ->update([
                'value' => "Bedankt, :name. Lysander bevestigt je sessie voor :datetime via e-mail of WhatsApp binnen 1\xe2\x80\x932 werkdagen.",
            ]);
    }

    public function down(): void
    {
        // No revert needed — this is a pure encoding fix
    }
};
