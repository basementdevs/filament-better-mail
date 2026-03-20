<?php

namespace Basement\BetterMails\Core\Listeners;

use Basement\BetterMails\Core\Actions\CreateBetterMailAction;
use Basement\BetterMails\Core\DTOs\BetterMailDTO;
use Basement\BetterMails\Core\Models\BetterEmail;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mime\Part\DataPart;

class BeforeSendingMailListener
{
    public function handle(MessageSending $event): void
    {
        $uuid = Uuid::uuid4();

        $mail = CreateBetterMailAction::execute(
            BetterMailDTO::fromBeforeSend([
                'uuid' => $uuid,
                'mailer' => $event->data['mailer'],
                'subject' => $event->message->getSubject() ?? null,
                'html' => $event->message->getHtmlBody() ?? null,
                'text' => $event->message->getTextBody() ?? null,
                'from' => $event->message->getFrom() ?? null,
                'to' => $event->message->getTo() ?? null,
                'reply_to' => $event->message->getReplyTo() ?? null,
                'cc' => $event->message->getCc() ?? null,
                'bcc' => $event->message->getBcc() ?? null,
                'mail_class' => $event->data['__laravel_mailable'] ?? null,
                'transport' => config('mail.mailers.'.$event->data['mailer'].'.transport', 'smtp'),
            ]),
        );

        if (config('filament-better-mails.mails.logging.attachments.enabled', true)) {
            $this->storeAttachments($event->message->getAttachments(), $mail);
        }

        $event->message->getHeaders()->addTextHeader(config('filament-better-mails.mails.headers.key'), $uuid);
    }

    /** @param DataPart[] $attachments */
    private function storeAttachments(array $attachments, BetterEmail $mail): void
    {
        if (empty($attachments)) {
            return;
        }

        $disk = config('filament-better-mails.mails.logging.attachments.disk', 'local');
        $root = config('filament-better-mails.mails.logging.attachments.root', 'mails/attachments');

        foreach ($attachments as $part) {
            $filename = $part->getFilename() ?? 'attachment';
            $content = $part->getBody();

            $attachment = $mail->attachments()->create([
                'disk' => $disk,
                'uuid' => Str::uuid()->toString(),
                'filename' => $filename,
                'mime' => $part->getContentType(),
                'inline' => $part->getDisposition() === 'inline',
                'size' => strlen($content),
            ]);

            Storage::disk($disk)->put(
                $root.'/'.$attachment->getKey().'/'.$filename,
                $content,
            );
        }
    }
}
