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
        public readonly string $attachmentMime,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Test] Better Mails — Attachment Test',
            tags: ['test-email'],
            metadata: ['source' => 'filament-better-mails'],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'basement-better-mails::mails.test.attachment',
            with: ['link' => $this->link],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->attachmentContent, $this->attachmentName)
                ->withMime($this->attachmentMime),
        ];
    }
}
