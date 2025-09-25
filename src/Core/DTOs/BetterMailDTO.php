<?php

namespace Basement\BetterMails\Core\DTOs;

use Basement\BetterMails\Core\Enums\SupportedMailProviders;

final readonly class BetterMailDTO
{
    public function __construct(
        public ?string $uuid,
        public string $mailer,
        public ?string $subject,
        public ?string $html,
        public ?string $text,
        public null|array|string $from,
        public null|array|string $to,
        public null|array|string $reply_to,
        public null|array|string $cc,
        public null|array|string $bcc,
        public ?string $mail_class,
        public SupportedMailProviders $transport,
    ) {}

    public static function make(array $data): self
    {
        return new self(
            uuid: $data['uuid'],
            mailer: $data['mailer'],
            subject: $data['subject'],
            html: $data['html'],
            text: $data['text'],
            from: $data['from'],
            to: $data['to'],
            reply_to: $data['reply_to'],
            cc: $data['cc'],
            bcc: $data['bcc'],
            mail_class: $data['mail_class'],
            transport: SupportedMailProviders::from($data['transport']),
        );
    }

    public static function fromBeforeSend(array $data): self
    {
        return new self(
            uuid: $data['uuid'],
            mailer: $data['mailer'],
            subject: $data['subject'],
            html: $data['html'],
            text: $data['text'],
            from: $data['from'],
            to: $data['to'],
            reply_to: $data['reply_to'],
            cc: $data['cc'],
            bcc: $data['bcc'],
            mail_class: $data['mail_class'],
            transport: SupportedMailProviders::from($data['transport']),
        );
    }
}
