<?php

namespace App\Services;

use App\Mail\Admin\NewBookingAlertMail;
use App\Mail\Admin\NewContactAlertMail;
use App\Mail\Client\BookingApprovedMail;
use App\Mail\Client\BookingConfirmationMail;
use App\Mail\Client\BookingRejectedMail;
use App\Mail\Client\ContactConfirmationMail;
use App\Models\Booking;
use App\Models\ContactSubmission;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendContactConfirmation(ContactSubmission $contact): void
    {
        if (!$this->shouldSend('notify_contact_confirmation')) {
            return;
        }

        Mail::to($contact->email)->queue(new ContactConfirmationMail($contact));
    }

    public function sendBookingConfirmation(Booking $booking): void
    {
        if (!$this->shouldSend('notify_booking_confirmation')) {
            return;
        }

        Mail::to($booking->email)->queue(new BookingConfirmationMail($booking));
    }

    public function sendBookingApproved(Booking $booking): void
    {
        if (!$this->shouldSend('notify_booking_approved')) {
            return;
        }

        Mail::to($booking->email)->queue(new BookingApprovedMail($booking));
    }

    public function sendBookingRejected(Booking $booking): void
    {
        if (!$this->shouldSend('notify_booking_rejected')) {
            return;
        }

        Mail::to($booking->email)->queue(new BookingRejectedMail($booking));
    }

    public function alertAdminNewContact(ContactSubmission $contact): void
    {
        if (!$this->shouldSend('notify_admin_new_contact')) {
            return;
        }

        $adminEmail = $this->getAdminEmail();
        if (!$adminEmail) {
            return;
        }

        Mail::to($adminEmail)->queue(new NewContactAlertMail($contact));
    }

    public function alertAdminNewBooking(Booking $booking): void
    {
        if (!$this->shouldSend('notify_admin_new_booking')) {
            return;
        }

        $adminEmail = $this->getAdminEmail();
        if (!$adminEmail) {
            return;
        }

        Mail::to($adminEmail)->queue(new NewBookingAlertMail($booking));
    }

    private function shouldSend(string $settingKey): bool
    {
        if (!$this->isMailConfigured()) {
            Log::info("NotificationService: Mail not configured, skipping [{$settingKey}].");
            return false;
        }

        return $this->isEnabled($settingKey);
    }

    private function isEnabled(string $settingKey): bool
    {
        $setting = SiteSetting::where('key', $settingKey)->first();
        return $setting && (bool) $setting->value;
    }

    private function isMailConfigured(): bool
    {
        $driver = config('mail.default');

        if ($driver === 'log') {
            // Log driver is still valid for development — allow sending (emails get logged)
            return true;
        }

        if ($driver === 'smtp') {
            return !empty(config('mail.mailers.smtp.host'));
        }

        return true;
    }

    private function getAdminEmail(): ?string
    {
        $setting = SiteSetting::where('key', 'admin_notification_email')->first();
        return $setting?->value ?: null;
    }
}
