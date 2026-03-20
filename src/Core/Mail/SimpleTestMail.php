<?php

namespace Basement\BetterMails\Core\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class SimpleTestMail extends Mailable
{
    public function __construct(public readonly string $link) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[Test] Better Mails — Simple Test');
    }

    public function content(): Content
    {
        return new Content(markdown: 'filament-better-mails::mails.test.simple');
    }
}
