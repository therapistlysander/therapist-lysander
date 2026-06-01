<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('headline')->nullable()->after('client_name');
            $table->text('body')->nullable()->after('headline');
            $table->string('tag', 100)->nullable()->after('body');
        });

        // Migrate existing data from old columns
        DB::table('testimonials')->whereNotNull('quote')->where('quote', '!=', '')->update([
            'body' => DB::raw('quote'),
        ]);

        DB::table('testimonials')->whereNotNull('client_title')->where('client_title', '!=', '')->update([
            'tag' => DB::raw('client_title'),
        ]);
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['headline', 'body', 'tag']);
        });
    }
};
