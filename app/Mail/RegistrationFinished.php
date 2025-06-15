<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address; // <-- CORRECT USE STATEMENT

use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationFinished extends Mailable
{
    use Queueable, SerializesModels;


    public Registration $registration;

    /**
     * Create a new message instance.
     */
    public function __construct($registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registration Finished',
            from: new Address('no-reply@xx.com', 'Nome da Sua Aplicação'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registrations.finished', // This will be your Blade Markdown email template
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
