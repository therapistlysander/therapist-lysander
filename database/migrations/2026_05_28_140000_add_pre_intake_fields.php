<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_intake_responses', function (Blueprint $table) {
            $table->text('brings_to_therapy')->nullable()->after('presenting_issue');
            $table->json('support_areas')->nullable()->after('brings_to_therapy');
            $table->string('communication_style')->nullable()->after('support_areas');
            $table->string('duration_expectation')->nullable()->after('communication_style');
        });
    }

    public function down(): void
    {
        Schema::table('pre_intake_responses', function (Blueprint $table) {
            $table->dropColumn(['brings_to_therapy', 'support_areas', 'communication_style', 'duration_expectation']);
        });
    }
};
