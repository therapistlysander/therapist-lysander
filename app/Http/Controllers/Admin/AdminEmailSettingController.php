<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminEmailSettingController extends Controller
{
    public function index()
    {
        $emailSettings = SiteSetting::where('group', 'email')
            ->orderBy('id')
            ->get()
            ->keyBy('key');

        $notificationSettings = SiteSetting::where('group', 'notifications')
            ->orderBy('id')
            ->get()
            ->keyBy('key');

        return view('admin.pages.settings.email', compact('emailSettings', 'notificationSettings'));
    }

    public function update(Request $request)
    {
        $data = $request->input('settings', []);

        foreach ($data as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            if (!$setting) {
                continue;
            }

            if ($setting->type === 'boolean') {
                $value = $value ? '1' : '0';
            }

            $setting->update(['value' => $value]);
        }

        // Handle unchecked boolean checkboxes (they won't be in the request)
        $booleanKeys = SiteSetting::where('group', 'notifications')
            ->where('type', 'boolean')
            ->pluck('key')
            ->toArray();

        foreach ($booleanKeys as $boolKey) {
            if (!isset($data[$boolKey])) {
                SiteSetting::where('key', $boolKey)->update(['value' => '0']);
            }
        }

        return redirect()->route('admin.email-settings.index')
            ->with('success', 'Email & notification settings saved.');
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:255',
        ]);

        try {
            // Temporarily override mail config with current DB settings
            $emailSettings = SiteSetting::where('group', 'email')
                ->pluck('value', 'key')
                ->toArray();

            $driver = $emailSettings['mail_driver'] ?? 'log';

            if ($driver === 'smtp' && !empty($emailSettings['smtp_host'])) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => $emailSettings['smtp_host'],
                    'mail.mailers.smtp.port' => (int) ($emailSettings['smtp_port'] ?? 587),
                    'mail.mailers.smtp.username' => $emailSettings['smtp_username'] ?? null,
                    'mail.mailers.smtp.password' => $emailSettings['smtp_password'] ?? null,
                    'mail.mailers.smtp.encryption' => $emailSettings['smtp_encryption'] ?? 'tls',
                ]);
            }

            if (!empty($emailSettings['mail_from_address'])) {
                config(['mail.from.address' => $emailSettings['mail_from_address']]);
            }
            if (!empty($emailSettings['mail_from_name'])) {
                config(['mail.from.name' => $emailSettings['mail_from_name']]);
            }

            // Clear cached transport so new config is used
            Mail::purge('smtp');

            Mail::raw('This is a test email from Therapist Lysander CMS. If you received this, your email configuration is working correctly.', function ($message) use ($request) {
                $message->to($request->input('test_email'))
                        ->subject('Test Email - Therapist Lysander CMS');
            });

            return back()->with('success', 'Test email sent successfully to ' . $request->input('test_email') . '.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
