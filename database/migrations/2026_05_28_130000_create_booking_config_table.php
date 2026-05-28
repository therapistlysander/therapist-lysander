<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add per-day start/end time overrides
        Schema::table('booking_availabilities', function (Blueprint $table) {
            $table->string('start_time', 5)->nullable()->after('time_slots'); // e.g. "09:00"
            $table->string('end_time', 5)->nullable()->after('start_time');   // e.g. "17:00"
        });

        // Global booking configuration
        Schema::create('booking_config', function (Blueprint $table) {
            $table->id();
            $table->integer('slot_duration')->default(30);       // minutes
            $table->string('default_start_time', 5)->default('09:00');
            $table->string('default_end_time', 5)->default('17:00');
            $table->string('break_start', 5)->nullable()->default('12:00');
            $table->string('break_end', 5)->nullable()->default('13:30');
            $table->timestamps();
        });

        // Seed default config
        DB::table('booking_config')->insert([
            'slot_duration'      => 30,
            'default_start_time' => '09:00',
            'default_end_time'   => '17:00',
            'break_start'        => '12:00',
            'break_end'          => '13:30',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // Clear old time_slots data - will be auto-generated now
        DB::table('booking_availabilities')->update([
            'time_slots'  => '[]',
            'start_time'  => null,
            'end_time'    => null,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_config');
        Schema::table('booking_availabilities', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
