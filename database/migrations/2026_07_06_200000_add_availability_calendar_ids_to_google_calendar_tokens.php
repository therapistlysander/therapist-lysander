<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add multi-calendar support for availability checking.
     * - availability_calendar_ids: calendars to check for busy times (read-only)
     * - calendar_id: remains the write target for new appointments
     */
    public function up(): void
    {
        Schema::table('google_calendar_tokens', function (Blueprint $table) {
            $table->json('availability_calendar_ids')->nullable()
                ->after('calendar_id');
        });
    }

    public function down(): void
    {
        Schema::table('google_calendar_tokens', function (Blueprint $table) {
            $table->dropColumn('availability_calendar_ids');
        });
    }
};
