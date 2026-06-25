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

class BookingConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your booking request was received',
        );
    }

    public function content(): Content
    {
        $formatLabels = [
            'intake'    => 'Introduction Call',
            'standard'  => 'Standard Session',
            'emdr'      => 'EMDR Session',
            'initial'   => 'Initial Session',
        ];

        $timezone = SiteSetting::where('key', 'timezone')->first()?->value ?: 'Europe/Amsterdam';

        return new Content(
            view: 'emails.client.booking-confirmation',
            with: [
                'firstName' => $this->booking->first_name,
                'sessionType' => $this->booking->session_type,
                'sessionFormat' => $formatLabels[$this->booking->session_format] ?? ucfirst($this->booking->session_format),
                'preferredDate' => $this->booking->preferred_date,
                'timezone' => $timezone,
                'clientTimezone' => $this->booking->client_timezone,
            ],
        );
    }
}
