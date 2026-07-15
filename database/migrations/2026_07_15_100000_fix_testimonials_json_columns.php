<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix testimonials and faqs columns to be JSON type for Spatie Translatable.
     */
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->json('headline')->nullable()->change();
            $table->json('body')->nullable()->change();
            $table->json('quote')->nullable()->change();
            $table->json('short_description')->nullable()->change();
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->json('question')->change();
            $table->json('answer')->change();
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('headline')->nullable()->change();
            $table->text('body')->nullable()->change();
            $table->text('quote')->nullable()->change();
            $table->text('short_description')->nullable()->change();
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->string('question')->change();
            $table->text('answer')->change();
        });
    }
};
