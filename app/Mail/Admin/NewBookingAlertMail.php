<?php

namespace App\Mail\Admin;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New booking: ' . $this->booking->first_name . ' ' . $this->booking->last_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-booking-alert',
            with: [
                'booking' => $this->booking,
            ],
        );
    }
}
