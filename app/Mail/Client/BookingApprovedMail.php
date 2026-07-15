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
        return new Envelope(
            subject: 'Your session is confirmed',
        );
    }

    public function content(): Content
    {
        $serverTimezone = SiteSetting::where('key', 'timezone')->first()?->value ?: 'Europe/Amsterdam';
        $clientTimezone = $this->booking->client_timezone;

        // Prefer the confirmed time; fall back to the requested time so the
        // details box is never empty when a booking is confirmed without an
        // explicit scheduling step (e.g. via the status dropdown).
        $scheduledAt = $this->booking->scheduled_at ?? $this->booking->preferred_date;
        $displayScheduledAt = null;
        if ($scheduledAt && $clientTimezone) {
            $displayScheduledAt = \Carbon\Carbon::parse($scheduledAt, $serverTimezone)
                ->setTimezone($clientTimezone)
                ->format('l, j F Y \a\t H:i');
        } elseif ($scheduledAt) {
            $displayScheduledAt = \Carbon\Carbon::parse($scheduledAt, $serverTimezone)
                ->format('l, j F Y \a\t H:i');
        }

        $formatLabels = [
            'intake'   => 'Introductory Call',
            'standard' => 'Standard Session',
            'emdr'     => 'EMDR Session',
            'initial'  => 'Initial Session',
        ];
        $sessionFormat = $this->booking->session_format;
        $appointmentType = $formatLabels[$sessionFormat] ?? ($sessionFormat ? ucfirst($sessionFormat) : null);

        return new Content(
            view: 'emails.client.booking-approved',
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
