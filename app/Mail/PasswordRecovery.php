<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordRecovery extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;

    public string $token;

    public function __construct($name, $token)
    {
        $this->name = $name;
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Recovery',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.password_recovery',
            with: [
                'name' => $this->name,
                'token' => $this->token,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
