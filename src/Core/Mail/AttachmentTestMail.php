<?php

namespace Basement\BetterMails\Core\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class AttachmentTestMail extends Mailable
{
    public function __construct(
        public readonly string $link,
        public readonly string $attachmentName,
        public readonly string $attachmentContent,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[Test] Better Mails — Attachment Test');
    }

    public function content(): Content
    {
        return new Content(markdown: 'filament-better-mails::mails.test.attachment');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->attachmentContent,
                $this->attachmentName,
            ),
        ];
    }
}
