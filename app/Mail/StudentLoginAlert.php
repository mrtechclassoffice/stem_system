<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentLoginAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $studentName, public string $time) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 Login Confirmation — STEM Academy',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student-login-alert',
        );
    }
}
