<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Booking/intro call requests
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('session_type')->nullable();       // online, in-person
            $table->string('preferred_language')->nullable(); // nl, en
            $table->text('reason')->nullable();               // brief reason for booking
            $table->string('source')->default('website');     // website, referral, etc.
            $table->string('status')->default('pending');     // pending, reviewed, scheduled, completed, cancelled
            $table->text('admin_notes')->nullable();
            $table->timestamp('preferred_date')->nullable();
            $table->string('calendly_event_id')->nullable();  // future calendar integration
            $table->timestamps();
        });

        // Pre-intake questionnaire responses (linked to a booking)
        Schema::create('pre_intake_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('preferred_language')->nullable();
            $table->text('presenting_issue');                  // main reason for seeking therapy
            $table->text('previous_therapy')->nullable();      // previous therapy experience
            $table->string('previous_therapy_type')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('relevant_history')->nullable();
            $table->boolean('crisis_risk')->default(false);    // safety screening
            $table->text('crisis_details')->nullable();
            $table->string('session_preference')->nullable();  // online / in-person
            $table->json('availability')->nullable();          // days/times preferred
            $table->text('additional_notes')->nullable();
            $table->string('status')->default('pending');      // pending, reviewed, archived
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_intake_responses');
        Schema::dropIfExists('bookings');
    }
};
