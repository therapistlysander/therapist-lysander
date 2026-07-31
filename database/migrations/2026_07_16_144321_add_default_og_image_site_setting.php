<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        SiteSetting::updateOrCreate(
            ['key' => 'default_og_image'],
            [
                'group' => 'social',
                'key'   => 'default_og_image',
                'value' => '/images/og-image.jpg',
                'type'  => 'image',
                'label' => 'Default Social Share Image (Open Graph)',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SiteSetting::where('key', 'default_og_image')->delete();
    }
};
