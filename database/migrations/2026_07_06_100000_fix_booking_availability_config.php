<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Set booking config to 09:00–16:00, 60-minute slots,
     * and restrict working days to Tue/Wed/Thu only.
     */
    public function up(): void
    {
        // Update global booking config
        DB::table('booking_config')->update([
            'slot_duration'      => 30,
            'default_start_time' => '09:00',
            'default_end_time'   => '16:00',
            'break_start'        => '12:00',
            'break_end'          => '13:30',
            'updated_at'         => now(),
        ]);

        // Set working days: only Tue(1), Wed(2), Thu(3) are active
        // 0=Mon, 1=Tue, 2=Wed, 3=Thu, 4=Fri, 5=Sat, 6=Sun
        DB::table('booking_availabilities')
            ->whereIn('day_of_week', [0, 4, 5, 6])
            ->update(['is_active' => false, 'start_time' => null, 'end_time' => null]);

        DB::table('booking_availabilities')
            ->whereIn('day_of_week', [1, 2, 3])
            ->update(['is_active' => true, 'start_time' => null, 'end_time' => null]);
    }

    public function down(): void
    {
        // No destructive changes to revert
    }
};
