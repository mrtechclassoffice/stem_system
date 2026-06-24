<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $email,
        public string $password
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎓 Welcome to STEM Academy — Your Login Details',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student-welcome',
        );
    }
}
