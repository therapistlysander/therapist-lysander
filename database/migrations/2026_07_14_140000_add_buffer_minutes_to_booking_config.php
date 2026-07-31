<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Add a configurable buffer (in minutes) between booking sessions.
 *
 * When set, the availability logic keeps at least this many minutes free
 * between the end of one appointment and the start of the next (and vice
 * versa), giving the therapist time for notes, a short break, or slight
 * overruns. Defaults to 15 minutes.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('booking_config')) {
            return;
        }

        if (!Schema::hasColumn('booking_config', 'buffer_minutes')) {
            Schema::table('booking_config', function (Blueprint $table) {
                $table->integer('buffer_minutes')->default(15)->after('break_end');
            });
        }

        // Ensure the existing singleton row has a sensible default.
        DB::table('booking_config')
            ->whereNull('buffer_minutes')
            ->update(['buffer_minutes' => 15]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('booking_config', 'buffer_minutes')) {
            Schema::table('booking_config', function (Blueprint $table) {
                $table->dropColumn('buffer_minutes');
            });
        }
    }
};
