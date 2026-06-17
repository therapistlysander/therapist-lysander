<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ui_translations', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index();
            $table->string('key')->index();
            $table->string('locale', 5);
            $table->text('value');
            $table->string('label')->nullable();
            $table->timestamps();
            
            $table->unique(['group', 'key', 'locale'], 'ui_translations_group_key_locale_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ui_translations');
    }
};
