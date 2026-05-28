<?php

namespace App\Mail\Client;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Regarding your booking request',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client.booking-rejected',
            with: [
                'firstName' => $this->booking->first_name,
                'rejectionReason' => $this->booking->rejection_reason,
            ],
        );
    }
}
