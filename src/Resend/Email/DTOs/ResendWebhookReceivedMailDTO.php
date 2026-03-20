<?php

namespace Basement\BetterMails\Resend\Email\DTOs;

// TODO: implement interface

use Basement\BetterMails\Core\Contracts\BetterMailDTOContract;
use Basement\BetterMails\Resend\Email\ResendEventsEnum;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

final readonly class ResendWebhookReceivedMailDTO implements BetterMailDTOContract, JsonSerializable
{
    public function __construct(
        public string $id,
        public ResendEventsEnum $event,
        public ?array $payload,
    ) {}

    public static function fromWebhook(array $dto): ?static
    {
        $event = ResendEventsEnum::tryFrom($dto['type'] ?? '');

        if ($event === null) {
            return null;
        }

        $mailUuid = $dto['data']['headers'][0]['value'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: $event,
            payload: $dto,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'mailUuid' => $this->id,
            'type' => $this->event->value,
            'payload' => $this->payload,
        ];
    }
}
