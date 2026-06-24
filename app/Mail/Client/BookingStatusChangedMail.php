<?php

namespace App\Mail\Client;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $newStatus,
    ) {
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'confirmed' => 'Your booking has been confirmed',
            'cancelled' => 'Your booking has been cancelled',
            'completed' => 'Your session has been completed',
            'no_show'   => 'Regarding your missed appointment',
        ];

        return new Envelope(
            subject: $subjects[$this->newStatus] ?? 'Your booking status has been updated',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client.booking-status-changed',
            with: [
                'firstName'   => $this->booking->first_name,
                'newStatus'   => $this->newStatus,
                'preferredDate' => $this->booking->preferred_date,
                'scheduledAt' => $this->booking->scheduled_at,
            ],
        );
    }
}
