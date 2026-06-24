<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminLoginAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $studentEmail,
        public string $time
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🔔 Login Alert: {$this->studentName} just logged in",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-login-alert',
        );
    }
}
