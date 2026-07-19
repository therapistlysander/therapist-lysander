<?php

namespace App\Mail\Client;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactSubmission $contact, public string $contactLocale = 'en')
    {
    }

    public function envelope(): Envelope
    {
        $isDutch = $this->contactLocale === 'nl';

        return new Envelope(
            subject: $isDutch
                ? 'Ik heb je bericht ontvangen'
                : 'We received your message',
        );
    }

    public function content(): Content
    {
        $isDutch = $this->contactLocale === 'nl';

        return new Content(
            view: $isDutch
                ? 'emails.client.contact-confirmation-nl'
                : 'emails.client.contact-confirmation',
            with: [
                'name' => $this->contact->name,
                'messageExcerpt' => \Illuminate\Support\Str::limit($this->contact->message, 150),
            ],
        );
    }
}
