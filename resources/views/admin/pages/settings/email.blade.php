@extends('admin.layouts.admin')
@section('title', 'Email & Notifications')
@section('page_title', 'Email & Notifications')

@section('content')

<form method="POST" action="{{ route('admin.email-settings.update') }}">
    @csrf
    @method('PATCH')

    <div class="admin-form">
        {{-- SMTP Configuration --}}
        <div class="admin-form__section">
            <div class="admin-form__section-title">SMTP Configuration</div>

            <div class="admin-field">
                <label class="admin-label">Mail Driver</label>
                <select name="settings[mail_driver]" class="admin-select">
                    <option value="log" {{ ($emailSettings['mail_driver']->value ?? 'log') === 'log' ? 'selected' : '' }}>Log (development - emails logged only)</option>
                    <option value="smtp" {{ ($emailSettings['mail_driver']->value ?? '') === 'smtp' ? 'selected' : '' }}>SMTP (production - emails sent)</option>
                </select>
                <small style="font-size:11px;color:#9ca3af;margin-top:4px;display:block;">Use "Log" for development (emails appear in storage/logs). Use "SMTP" to send real emails.</small>
            </div>

            <div class="admin-field">
                <label class="admin-label">SMTP Host</label>
                <input type="text" name="settings[smtp_host]" class="admin-input" value="{{ $emailSettings['smtp_host']->value ?? '' }}" placeholder="e.g. smtp.gmail.com, smtp.mailgun.org">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="admin-field">
                    <label class="admin-label">SMTP Port</label>
                    <input type="text" name="settings[smtp_port]" class="admin-input" value="{{ $emailSettings['smtp_port']->value ?? '587' }}" placeholder="587">
                </div>
                <div class="admin-field">
                    <label class="admin-label">Encryption</label>
                    <select name="settings[smtp_encryption]" class="admin-select">
                        <option value="tls" {{ ($emailSettings['smtp_encryption']->value ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (recommended)</option>
                        <option value="ssl" {{ ($emailSettings['smtp_encryption']->value ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ ($emailSettings['smtp_encryption']->value ?? 'tls') === '' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
            </div>

            <div class="admin-field">
                <label class="admin-label">SMTP Username</label>
                <input type="text" name="settings[smtp_username]" class="admin-input" value="{{ $emailSettings['smtp_username']->value ?? '' }}" placeholder="your-email@domain.com" autocomplete="off">
            </div>

            <div class="admin-field">
                <label class="admin-label">SMTP Password</label>
                <input type="password" name="settings[smtp_password]" class="admin-input" value="{{ $emailSettings['smtp_password']->value ?? '' }}" placeholder="App password or SMTP key" autocomplete="new-password">
            </div>
        </div>

        {{-- Sender Identity --}}
        <div class="admin-form__section">
            <div class="admin-form__section-title">Sender Identity</div>

            <div class="admin-field">
                <label class="admin-label">From Name</label>
                <input type="text" name="settings[mail_from_name]" class="admin-input" value="{{ $emailSettings['mail_from_name']->value ?? 'Therapist Lysander' }}" placeholder="Therapist Lysander">
            </div>

            <div class="admin-field">
                <label class="admin-label">From Email Address</label>
                <input type="email" name="settings[mail_from_address]" class="admin-input" value="{{ $emailSettings['mail_from_address']->value ?? '' }}" placeholder="noreply@yourdomain.com">
            </div>
        </div>

        {{-- Notification Preferences --}}
        <div class="admin-form__section">
            <div class="admin-form__section-title">Notification Preferences</div>

            <p style="font-size:12px;color:#6b7280;margin:0 0 16px;">Choose which email notifications are sent automatically.</p>

            <div style="margin-bottom:20px;">
                <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin:0 0 10px;">Client Notifications</p>

                <label style="display:flex;align-items:center;gap:10px;padding:8px 0;cursor:pointer;">
                    <input type="checkbox" name="settings[notify_contact_confirmation]" value="1" {{ ($notificationSettings['notify_contact_confirmation']->value ?? '1') === '1' ? 'checked' : '' }} style="accent-color:#5a9e97;width:16px;height:16px;">
                    <span style="font-size:13px;color:#374151;">Send contact form confirmation to client</span>
                </label>

                <label style="display:flex;align-items:center;gap:10px;padding:8px 0;cursor:pointer;">
                    <input type="checkbox" name="settings[notify_booking_confirmation]" value="1" {{ ($notificationSettings['notify_booking_confirmation']->value ?? '1') === '1' ? 'checked' : '' }} style="accent-color:#5a9e97;width:16px;height:16px;">
                    <span style="font-size:13px;color:#374151;">Send booking confirmation to client</span>
                </label>

                <label style="display:flex;align-items:center;gap:10px;padding:8px 0;cursor:pointer;">
                    <input type="checkbox" name="settings[notify_booking_approved]" value="1" {{ ($notificationSettings['notify_booking_approved']->value ?? '1') === '1' ? 'checked' : '' }} style="accent-color:#5a9e97;width:16px;height:16px;">
                    <span style="font-size:13px;color:#374151;">Send approval/scheduled email to client</span>
                </label>

                <label style="display:flex;align-items:center;gap:10px;padding:8px 0;cursor:pointer;">
                    <input type="checkbox" name="settings[notify_booking_rejected]" value="1" {{ ($notificationSettings['notify_booking_rejected']->value ?? '1') === '1' ? 'checked' : '' }} style="accent-color:#5a9e97;width:16px;height:16px;">
                    <span style="font-size:13px;color:#374151;">Send rejection email to client</span>
                </label>
            </div>

            <div style="margin-bottom:20px;">
                <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin:0 0 10px;">Admin Notifications</p>

                <label style="display:flex;align-items:center;gap:10px;padding:8px 0;cursor:pointer;">
                    <input type="checkbox" name="settings[notify_admin_new_contact]" value="1" {{ ($notificationSettings['notify_admin_new_contact']->value ?? '1') === '1' ? 'checked' : '' }} style="accent-color:#5a9e97;width:16px;height:16px;">
                    <span style="font-size:13px;color:#374151;">Alert admin on new contact submission</span>
                </label>

                <label style="display:flex;align-items:center;gap:10px;padding:8px 0;cursor:pointer;">
                    <input type="checkbox" name="settings[notify_admin_new_booking]" value="1" {{ ($notificationSettings['notify_admin_new_booking']->value ?? '1') === '1' ? 'checked' : '' }} style="accent-color:#5a9e97;width:16px;height:16px;">
                    <span style="font-size:13px;color:#374151;">Alert admin on new booking request</span>
                </label>
            </div>

            <div class="admin-field">
                <label class="admin-label">Admin Notification Email</label>
                <input type="email" name="settings[admin_notification_email]" class="admin-input" value="{{ $notificationSettings['admin_notification_email']->value ?? '' }}" placeholder="admin@yourdomain.com">
                <small style="font-size:11px;color:#9ca3af;margin-top:4px;display:block;">Admin alert emails will be sent to this address.</small>
            </div>
        </div>

        {{-- Actions --}}
        <div class="admin-form__section" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <button type="submit" class="btn-admin btn-admin--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Save Settings
            </button>
        </div>
    </div>
</form>

{{-- Test Email Section --}}
<div class="admin-form" style="margin-top:24px;">
    <div class="admin-form__section">
        <div class="admin-form__section-title">Send Test Email</div>
        <p style="font-size:12px;color:#6b7280;margin:0 0 16px;">Verify your SMTP configuration works by sending a test email. Make sure to save your settings first.</p>

        <form method="POST" action="{{ route('admin.email-settings.test') }}" style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;">
            @csrf
            <div class="admin-field" style="flex:1;min-width:250px;margin-bottom:0;">
                <label class="admin-label">Recipient Email</label>
                <input type="email" name="test_email" class="admin-input" value="{{ $notificationSettings['admin_notification_email']->value ?? '' }}" placeholder="test@example.com" required>
            </div>
            <button type="submit" class="btn-admin btn-admin--outline" style="white-space:nowrap;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                Send Test Email
            </button>
        </form>
    </div>
</div>

@endsection
