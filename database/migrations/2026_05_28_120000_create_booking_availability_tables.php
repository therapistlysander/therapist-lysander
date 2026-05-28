<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Weekly schedule: one row per day of week (0=Mon .. 6=Sun)
        Schema::create('booking_availabilities', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('day_of_week'); // 0=Monday, 1=Tuesday ... 6=Sunday
            $table->json('time_slots');          // ["09:00","09:30","10:00", ...]
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('day_of_week');
        });

        // Specific date overrides (blocked dates or partial blocks)
        Schema::create('booking_blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->date('blocked_date');
            $table->json('blocked_slots')->nullable(); // null = whole day blocked, array = specific slots blocked
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique('blocked_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_blocked_dates');
        Schema::dropIfExists('booking_availabilities');
    }
};
