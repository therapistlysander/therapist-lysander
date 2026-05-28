<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('session_format')->nullable()->after('session_type');    // intake, standard, emdr, initial
            $table->timestamp('scheduled_at')->nullable()->after('preferred_date'); // confirmed session datetime
            $table->string('meeting_link')->nullable()->after('scheduled_at');      // video call URL
            $table->string('meeting_platform')->nullable()->after('meeting_link');  // zoom, google_meet, teams, other
            $table->timestamp('confirmed_at')->nullable()->after('meeting_platform');
            $table->text('rejection_reason')->nullable()->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'session_format',
                'scheduled_at',
                'meeting_link',
                'meeting_platform',
                'confirmed_at',
                'rejection_reason',
            ]);
        });
    }
};
