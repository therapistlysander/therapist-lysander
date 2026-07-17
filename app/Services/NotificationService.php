<?php

namespace App\Services;

use App\Mail\Admin\NewBookingAlertMail;
use App\Mail\Admin\NewContactAlertMail;
use App\Mail\Client\BookingApprovedMail;
use App\Mail\Client\BookingConfirmationMail;
use App\Mail\Client\BookingRejectedMail;
use App\Mail\Client\BookingStatusChangedMail;
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

        // Capture the current website locale now (during the web request) so the
        // queued mail is rendered in the language of the site the visitor used.
        $locale = app()->getLocale();

        Mail::to($contact->email)->queue(new ContactConfirmationMail($contact, $locale));
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

    /**
     * Send status change notification when admin updates booking status via dropdown.
     */
    public function sendBookingStatusChanged(Booking $booking, string $newStatus): void
    {
        // For confirmed/cancelled, reuse existing dedicated emails
        if ($newStatus === 'confirmed') {
            $this->sendBookingApproved($booking);
            return;
        }
        if ($newStatus === 'cancelled') {
            $this->sendBookingRejected($booking);
            return;
        }

        // For completed, no_show, etc.
        if (!$this->shouldSend('notify_booking_approved')) {
            return;
        }

        Mail::to($booking->email)->queue(new BookingStatusChangedMail($booking, $newStatus));
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
