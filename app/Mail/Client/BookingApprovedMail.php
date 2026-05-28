<?php

namespace App\Mail\Client;

use App\Models\Booking;
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
        return new Content(
            view: 'emails.client.booking-approved',
            with: [
                'firstName' => $this->booking->first_name,
                'scheduledAt' => $this->booking->scheduled_at,
                'meetingLink' => $this->booking->meeting_link,
                'meetingPlatform' => $this->booking->meeting_platform,
            ],
        );
    }
}
