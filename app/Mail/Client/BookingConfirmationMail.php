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
        $isDutch = $this->booking->preferred_language === 'nl';

        return new Envelope(
            subject: $isDutch
                ? 'Je boekingsverzoek is ontvangen'
                : 'Your booking request was received',
        );
    }

    public function content(): Content
    {
        $isDutch = $this->booking->preferred_language === 'nl';

        $typeLabels = [
            'intake'    => $isDutch ? 'Kennismakingsgesprek' : 'Introductory Call',
            'standard'  => $isDutch ? 'Standaard sessie' : 'Standard Session',
            'emdr'      => 'EMDR Session',
            'initial'   => $isDutch ? 'Eerste sessie' : 'Initial Session',
        ];

        $formatLabels = [
            'intake'    => $isDutch ? 'Kennismakingsgesprek' : 'Introductory Call',
            'standard'  => $isDutch ? 'Standaard sessie' : 'Standard Session',
            'emdr'      => 'EMDR Session',
            'initial'   => $isDutch ? 'Eerste sessie' : 'Initial Session',
        ];

        $serverTimezone = SiteSetting::where('key', 'timezone')->first()?->value ?: 'Europe/Amsterdam';
        $clientTimezone = $this->booking->client_timezone;

        // Convert preferred_date from server timezone to client's timezone.
        // Dutch keeps 24-hour "om HH:mm"; English uses 12-hour "at h:mm AM/PM".
        $preferredDate = $this->booking->preferred_date;
        $displayDate = $preferredDate;
        $dateFormat = $isDutch
            ? 'D MMMM YYYY [om] HH:mm'
            : 'D MMMM YYYY [at] h:mm A';
        if ($preferredDate && $clientTimezone) {
            $displayDate = \Carbon\Carbon::parse($preferredDate, $serverTimezone)
                ->setTimezone($clientTimezone)
                ->locale($isDutch ? 'nl' : 'en')
                ->isoFormat($dateFormat);
        } elseif ($preferredDate) {
            $displayDate = \Carbon\Carbon::parse($preferredDate, $serverTimezone)
                ->locale($isDutch ? 'nl' : 'en')
                ->isoFormat($dateFormat);
        }

        $view = $isDutch
            ? 'emails.client.booking-confirmation-nl'
            : 'emails.client.booking-confirmation';

        return new Content(
            view: $view,
            with: [
                'firstName'       => $this->booking->first_name,
                'sessionType'     => $this->booking->session_type,
                'appointmentType' => $typeLabels[$this->booking->session_format] ?? ucfirst($this->booking->session_format),
                'sessionFormat'   => $formatLabels[$this->booking->session_format] ?? ucfirst($this->booking->session_format),
                'displayDate'     => $displayDate,
                'clientTimezone'  => $clientTimezone,
            ],
        );
    }
}
