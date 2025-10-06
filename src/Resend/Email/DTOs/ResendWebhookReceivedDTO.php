<?php

namespace Basement\BetterMails\Resend\Email\DTOs;

// TODO: implement interface

use Basement\BetterMails\Core\Contracts\BetterDTOContract;
use Basement\BetterMails\Resend\Email\ResendEventsEnum;

final readonly class ResendWebhookReceivedDTO implements BetterDTOContract
{
    public function __construct(
        public string $mailUuid,
        public ResendEventsEnum $event,
    ) {}

    public static function fromWebhook(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
        );
    }
}
