<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code, public string $name = '', public bool $isAdmin = false)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "{$this->code} is your EG Tech Solution password reset code");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.password-reset-code');
    }
}
