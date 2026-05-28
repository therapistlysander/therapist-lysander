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

    public function __construct(public ContactSubmission $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your message',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client.contact-confirmation',
            with: [
                'name' => $this->contact->name,
                'messageExcerpt' => \Illuminate\Support\Str::limit($this->contact->message, 150),
            ],
        );
    }
}
