<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update EN translations
        DB::table('ui_translations')
            ->where('locale', 'en')
            ->where('group', 'booking')
            ->where('key', 'booking_disclaimer')
            ->update([
                'value' => "Your booking request will be sent to Lysander. You'll receive a confirmation via email or WhatsApp within 1\xe2\x80\x932 business days.",
            ]);

        DB::table('ui_translations')
            ->where('locale', 'en')
            ->where('group', 'booking')
            ->where('key', 'success_desc')
            ->update([
                'value' => 'Thank you, :name. Lysander will confirm your session for :datetime via email or WhatsApp within 1\xe2\x80\x932 business days.',
            ]);

        // Update NL translations
        DB::table('ui_translations')
            ->where('locale', 'nl')
            ->where('group', 'booking')
            ->where('key', 'booking_disclaimer')
            ->update([
                'value' => "Je boekingsverzoek wordt naar Lysander gestuurd. Je ontvangt binnen 1\xe2\x80\x932 werkdagen een bevestiging via e-mail of WhatsApp.",
            ]);

        DB::table('ui_translations')
            ->where('locale', 'nl')
            ->where('group', 'booking')
            ->where('key', 'success_desc')
            ->update([
                'value' => 'Bedankt, :name. Lysander bevestigt je sessie voor :datetime via e-mail of WhatsApp binnen 1\xe2\x80\x932 werkdagen.',
            ]);
    }

    public function down(): void
    {
        // Revert to original values
        DB::table('ui_translations')
            ->where('locale', 'en')
            ->where('group', 'booking')
            ->where('key', 'booking_disclaimer')
            ->update([
                'value' => "Your booking request will be sent to Lysander. You'll receive a confirmation via email or WhatsApp within 24 hours.",
            ]);

        DB::table('ui_translations')
            ->where('locale', 'en')
            ->where('group', 'booking')
            ->where('key', 'success_desc')
            ->update([
                'value' => 'Thank you, :name. Lysander will confirm your session for :datetime via email or WhatsApp within 24 hours.',
            ]);

        DB::table('ui_translations')
            ->where('locale', 'nl')
            ->where('group', 'booking')
            ->where('key', 'booking_disclaimer')
            ->update([
                'value' => 'Je boekingsverzoek wordt naar Lysander gestuurd. Je ontvangt binnen 24 uur een bevestiging via e-mail of WhatsApp.',
            ]);

        DB::table('ui_translations')
            ->where('locale', 'nl')
            ->where('group', 'booking')
            ->where('key', 'success_desc')
            ->update([
                'value' => 'Bedankt, :name. Lysander bevestigt je sessie voor :datetime via e-mail of WhatsApp binnen 24 uur.',
            ]);
    }
};
