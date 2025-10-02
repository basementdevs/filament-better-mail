<?php

namespace Basement\BetterMails\Core\DTOs;

use Basement\BetterMails\Core\Models\BetterEmail;

final readonly class ResendMailDTO
{
    public function __construct(
        public BetterEmail $mail,
        public array $to,
        public array $cc = [],
        public array $bcc = [],
        public array $replyTo = []
    ) {}

    public static function make(array $data): self
    {
        return new self(
            mail: $data['mail'],
            to: $data['to'],
            cc: $data['cc'],
            bcc: $data['bcc'],
        );
    }
}
