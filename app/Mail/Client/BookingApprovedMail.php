<?php

namespace App\Mail\Client;

use App\Models\Booking;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        $isDutch = $this->booking->preferred_language === 'nl';

        return new Envelope(
            subject: $isDutch
                ? 'Je sessie is bevestigd'
                : 'Your session is confirmed',
        );
    }

    public function content(): Content
    {
        $isDutch = $this->booking->preferred_language === 'nl';

        $serverTimezone = SiteSetting::where('key', 'timezone')->first()?->value ?: 'Europe/Amsterdam';
        $clientTimezone = $this->booking->client_timezone;

        // Prefer the confirmed time; fall back to the requested time so the
        // details box is never empty when a booking is confirmed without an
        // explicit scheduling step (e.g. via the status dropdown).
        // Dutch uses 24-hour "om HH:mm"; English uses 12-hour "at h:mm AM/PM".
        $scheduledAt = $this->booking->scheduled_at ?? $this->booking->preferred_date;
        $dateFormat = $isDutch
            ? 'D MMMM YYYY [om] HH:mm'
            : 'D MMMM YYYY [at] h:mm A';
        $displayScheduledAt = null;
        if ($scheduledAt) {
            $carbon = \Carbon\Carbon::parse($scheduledAt, $serverTimezone);
            if ($clientTimezone) {
                $carbon->setTimezone($clientTimezone);
            }
            $displayScheduledAt = $carbon->locale($isDutch ? 'nl' : 'en')->isoFormat($dateFormat);
        }

        $formatLabels = [
            'intake'   => $isDutch ? 'Kennismakingsgesprek' : 'Introductory Call',
            'standard' => $isDutch ? 'Standaard sessie' : 'Standard Session',
            'emdr'     => 'EMDR Session',
            'initial'  => $isDutch ? 'Eerste sessie' : 'Initial Session',
        ];
        $sessionFormat = $this->booking->session_format;
        $appointmentType = $formatLabels[$sessionFormat] ?? ($sessionFormat ? ucfirst($sessionFormat) : null);

        return new Content(
            view: $isDutch
                ? 'emails.client.booking-approved-nl'
                : 'emails.client.booking-approved',
            with: [
                'firstName' => $this->booking->first_name,
                'appointmentType' => $appointmentType,
                'sessionType' => $this->booking->session_type,
                'displayScheduledAt' => $displayScheduledAt,
                'meetingLink' => $this->booking->meeting_link,
                'meetingPlatform' => $this->booking->meeting_platform,
                'clientTimezone' => $clientTimezone,
            ],
        );
    }
}
