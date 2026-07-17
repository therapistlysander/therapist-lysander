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
        $isDutch = $this->booking->preferred_language === 'nl';

        $subjects = [
            'confirmed' => $isDutch ? 'Je afspraak is bevestigd' : 'Your booking has been confirmed',
            'cancelled' => $isDutch ? 'Je afspraak is geannuleerd' : 'Your booking has been cancelled',
            'completed' => $isDutch ? 'Je sessie is voltooid' : 'Your session has been completed',
            'no_show'   => $isDutch ? 'Betreft je gemiste afspraak' : 'Regarding your missed appointment',
        ];

        return new Envelope(
            subject: $subjects[$this->newStatus] ?? ($isDutch ? 'Je afspraakstatus is bijgewerkt' : 'Your booking status has been updated'),
        );
    }

    public function content(): Content
    {
        $isDutch = $this->booking->preferred_language === 'nl';

        $view = $isDutch
            ? 'emails.client.booking-status-changed-nl'
            : 'emails.client.booking-status-changed';

        return new Content(
            view: $view,
            with: [
                'firstName'   => $this->booking->first_name,
                'newStatus'   => $this->newStatus,
                'preferredDate' => $this->booking->preferred_date,
                'scheduledAt' => $this->booking->scheduled_at,
            ],
        );
    }
}
