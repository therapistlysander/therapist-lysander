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
            ['key' => 'default_meeting_link'],
            [
                'group' => 'booking',
                'key'   => 'default_meeting_link',
                'value' => '',
                'type'  => 'string',
                'label' => 'Default Online Meeting Link',
            ]
        );

        SiteSetting::updateOrCreate(
            ['key' => 'default_meeting_platform'],
            [
                'group' => 'booking',
                'key'   => 'default_meeting_platform',
                'value' => 'zoom',
                'type'  => 'string',
                'label' => 'Default Meeting Platform',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SiteSetting::whereIn('key', ['default_meeting_link', 'default_meeting_platform'])->delete();
    }
};
