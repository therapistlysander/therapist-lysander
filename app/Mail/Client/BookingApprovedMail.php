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

        // Convert scheduled_at from server timezone to client's timezone
        $scheduledAt = $this->booking->scheduled_at;
        $displayScheduledAt = $scheduledAt;
        if ($scheduledAt && $clientTimezone) {
            $displayScheduledAt = \Carbon\Carbon::parse($scheduledAt, $serverTimezone)
                ->setTimezone($clientTimezone)
                ->format('l, j F Y \a\t H:i');
        } elseif ($scheduledAt) {
            $displayScheduledAt = \Carbon\Carbon::parse($scheduledAt, $serverTimezone)
                ->format('l, j F Y \a\t H:i');
        }

        return new Content(
            view: 'emails.client.booking-approved',
            with: [
                'firstName' => $this->booking->first_name,
                'displayScheduledAt' => $displayScheduledAt,
                'meetingLink' => $this->booking->meeting_link,
                'meetingPlatform' => $this->booking->meeting_platform,
                'clientTimezone' => $clientTimezone,
            ],
        );
    }
}
