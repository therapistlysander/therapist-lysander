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

        // preferred_date holds the Amsterdam wall-clock time the client booked.
        // Display that exact time with no timezone shift, so the email matches
        // the booking storage, the admin panel, and the Google Calendar event.
        // Dutch keeps 24-hour "om HH:mm"; English uses 12-hour "at h:mm AM/PM".
        $preferredDate = $this->booking->preferred_date;
        $displayDate = $preferredDate;
        $dateFormat = $isDutch
            ? 'D MMMM YYYY [om] HH:mm'
            : 'D MMMM YYYY [at] h:mm A';
        if ($preferredDate) {
            $displayDate = \Carbon\Carbon::parse($preferredDate->format('Y-m-d H:i:s'), $serverTimezone)
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
                'appointmentTimezone' => $serverTimezone,
                'reason'          => $this->booking->reason,
            ],
        );
    }
}
