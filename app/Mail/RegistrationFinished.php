<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationFinished extends Mailable
{
    use Queueable, SerializesModels;

    public Registration $registration;

    /**
     * Create a new message instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmação de Inscrição - Vem Dançar Sudamerica',
            from: new Address('naoresponda@vemdancarsudamerica.com.br', 'Vem Dançar Sudamerica'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.registration.finished', // Caminho correto da view
            with: [
                'registration' => $this->registration,
                'school' => $this->registration->school,
                'user' => $this->registration->school->user,
            ],
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