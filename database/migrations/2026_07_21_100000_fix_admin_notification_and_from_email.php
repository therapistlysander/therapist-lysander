<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')
            ->where('key', 'admin_notification_email')
            ->update(['value' => 'contact@therapistlysander.com']);

        DB::table('site_settings')
            ->where('key', 'mail_from_address')
            ->update(['value' => 'contact@therapistlysander.com']);
    }

    public function down(): void
    {
        // No rollback needed
    }
};
