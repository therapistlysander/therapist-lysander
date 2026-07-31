<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name',        'value' => 'Therapist Lysander',                    'type' => 'string', 'label' => 'Site Name'],
            ['group' => 'general', 'key' => 'tagline',          'value' => 'Psychologist & Trauma Therapist',        'type' => 'string', 'label' => 'Tagline'],
            ['group' => 'general', 'key' => 'multilingual_enabled', 'value' => '1',                              'type' => 'boolean', 'label' => 'Enable Multilingual'],
            ['group' => 'general', 'key' => 'language',         'value' => 'nl,en',                                  'type' => 'string', 'label' => 'Supported Languages'],
            ['group' => 'general', 'key' => 'timezone',         'value' => 'Europe/Amsterdam',                       'type' => 'string', 'label' => 'Timezone'],
            // Contact
            ['group' => 'contact', 'key' => 'contact_email',    'value' => 'contact@therapistlysander.com',          'type' => 'string', 'label' => 'Contact Email'],
            ['group' => 'contact', 'key' => 'contact_phone',    'value' => '',                                       'type' => 'string', 'label' => 'Phone Number'],
            ['group' => 'contact', 'key' => 'location_city',    'value' => 'Amsterdam',                              'type' => 'string', 'label' => 'Location / City'],
            ['group' => 'contact', 'key' => 'location_country', 'value' => 'Netherlands',                            'type' => 'string', 'label' => 'Country'],
            ['group' => 'contact', 'key' => 'calendly_url',     'value' => '',                                       'type' => 'string', 'label' => 'Calendly Booking URL'],
            // Booking & Sessions
            ['group' => 'booking', 'key' => 'default_meeting_link',     'value' => '',      'type' => 'string', 'label' => 'Default Online Meeting Link'],
            ['group' => 'booking', 'key' => 'default_meeting_platform', 'value' => 'zoom',  'type' => 'string', 'label' => 'Default Meeting Platform'],
            // Social
            ['group' => 'social',  'key' => 'linkedin_url',     'value' => '',                                       'type' => 'string', 'label' => 'LinkedIn URL'],
            ['group' => 'social',  'key' => 'instagram_url',    'value' => '',                                       'type' => 'string', 'label' => 'Instagram URL'],
            ['group' => 'social',  'key' => 'psychology_today_url', 'value' => '',                                   'type' => 'string', 'label' => 'Psychology Today Profile'],
            ['group' => 'social',  'key' => 'default_og_image', 'value' => '/images/og-image.jpg',                   'type' => 'image',  'label' => 'Default Social Share Image (Open Graph)'],
            // Analytics
            ['group' => 'analytics', 'key' => 'google_analytics_id', 'value' => '',                                  'type' => 'string', 'label' => 'Google Analytics ID'],
            ['group' => 'analytics', 'key' => 'gtm_id',              'value' => '',                                  'type' => 'string', 'label' => 'Google Tag Manager ID'],
            ['group' => 'analytics', 'key' => 'cookie_consent_enabled', 'value' => '1',                              'type' => 'boolean', 'label' => 'Enable Cookie Consent Banner'],
            // Email / SMTP
            ['group' => 'email', 'key' => 'mail_driver',        'value' => 'log',                                    'type' => 'string', 'label' => 'Mail Driver'],
            ['group' => 'email', 'key' => 'smtp_host',          'value' => '',                                       'type' => 'string', 'label' => 'SMTP Host'],
            ['group' => 'email', 'key' => 'smtp_port',          'value' => '587',                                    'type' => 'string', 'label' => 'SMTP Port'],
            ['group' => 'email', 'key' => 'smtp_username',      'value' => '',                                       'type' => 'string', 'label' => 'SMTP Username'],
            ['group' => 'email', 'key' => 'smtp_password',      'value' => '',                                       'type' => 'string', 'label' => 'SMTP Password'],
            ['group' => 'email', 'key' => 'smtp_encryption',    'value' => 'tls',                                    'type' => 'string', 'label' => 'Encryption'],
            ['group' => 'email', 'key' => 'mail_from_address',  'value' => 'noreply@therapistlysander.com',          'type' => 'string', 'label' => 'From Email Address'],
            ['group' => 'email', 'key' => 'mail_from_name',     'value' => 'Therapist Lysander',                     'type' => 'string', 'label' => 'From Name'],
            // Notifications
            ['group' => 'notifications', 'key' => 'notify_contact_confirmation', 'value' => '1', 'type' => 'boolean', 'label' => 'Send contact form confirmation to client'],
            ['group' => 'notifications', 'key' => 'notify_booking_confirmation', 'value' => '1', 'type' => 'boolean', 'label' => 'Send booking confirmation to client'],
            ['group' => 'notifications', 'key' => 'notify_booking_approved',     'value' => '1', 'type' => 'boolean', 'label' => 'Send approval email to client'],
            ['group' => 'notifications', 'key' => 'notify_booking_rejected',     'value' => '1', 'type' => 'boolean', 'label' => 'Send rejection email to client'],
            ['group' => 'notifications', 'key' => 'notify_admin_new_contact',    'value' => '1', 'type' => 'boolean', 'label' => 'Alert admin on new contact submission'],
            ['group' => 'notifications', 'key' => 'notify_admin_new_booking',    'value' => '1', 'type' => 'boolean', 'label' => 'Alert admin on new booking request'],
            ['group' => 'notifications', 'key' => 'admin_notification_email',    'value' => 'contact@therapistlysander.com', 'type' => 'string', 'label' => 'Admin notification email'],
        ];

        foreach ($settings as $data) {
            SiteSetting::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
