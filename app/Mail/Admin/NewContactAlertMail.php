<?php

namespace App\Mail\Admin;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactSubmission $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New contact: ' . $this->contact->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-contact-alert',
            with: [
                'contact' => $this->contact,
            ],
        );
    }
}
