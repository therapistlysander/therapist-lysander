<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Homepage sections (hero, CTA, process steps, etc.)
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page')->default('home'); // home, about, fees, etc.
            $table->string('section_key')->unique();  // hero, cta_1, cta_2, process, etc.
            $table->string('label')->nullable();      // admin-friendly label
            $table->json('content');                  // flexible JSON content
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // SEO metadata per page
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();    // home, about, fees, booking, faq, etc.
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->json('extra')->nullable();       // additional meta tags
            $table->timestamps();
        });

        // Global site settings (analytics IDs, contact info, social links, etc.)
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general'); // general, social, analytics, contact
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');   // string, boolean, json, text
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('seo_settings');
        Schema::dropIfExists('page_sections');
    }
};
