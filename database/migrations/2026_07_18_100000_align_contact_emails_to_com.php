<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Align contact-related email settings to the canonical .com domain so the
 * whole site is consistent (public pages already use contact@therapistlysander.com).
 *
 * Guarded + idempotent: each row is only updated when it still holds the old
 * default value, so a value an admin has already customised is never clobbered.
 * Fully reversible.
 */
return new class extends Migration
{
    /**
     * key => [old default, new value]
     */
    private array $map = [
        'contact_email'            => ['info@therapistlysander.nl',    'contact@therapistlysander.com'],
        'mail_from_address'        => ['noreply@therapistlysander.nl',  'noreply@therapistlysander.com'],
        'admin_notification_email' => ['admin@therapistlysander.nl',    'admin@therapistlysander.com'],
    ];

    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        foreach ($this->map as $key => [$old, $new]) {
            DB::table('site_settings')
                ->where('key', $key)
                ->where('value', $old)
                ->update(['value' => $new]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        foreach ($this->map as $key => [$old, $new]) {
            DB::table('site_settings')
                ->where('key', $key)
                ->where('value', $new)
                ->update(['value' => $old]);
        }
    }
};
