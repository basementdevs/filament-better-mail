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

    public static function fromWebhook(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailSent(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailDelivered(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailDeliveredDelayed(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailComplained(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailBounced(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailOpened(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailClicked(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailRecieved(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailFailed(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            id: Uuid::fromString($mailUuid),
            event: ResendEventsEnum::tryFrom($dto['type']),
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
