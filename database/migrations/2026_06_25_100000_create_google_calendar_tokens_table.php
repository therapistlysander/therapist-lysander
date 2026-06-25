<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_calendar_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('access_token');
            $table->text('refresh_token');
            $table->integer('token_expires_at');
            $table->string('calendar_id')->default('primary');
            $table->string('google_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('connected_at');
            $table->timestamp('last_synced_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_calendar_tokens');
    }
};
