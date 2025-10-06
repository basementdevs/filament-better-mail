<?php

namespace Basement\BetterMails\Resend\Email\DTOs;

// TODO: implement interface

use Basement\BetterMails\Core\Contracts\BetterDTOContract;
use Basement\BetterMails\Resend\Email\ResendEventsEnum;
use JsonSerializable;

final readonly class ResendWebhookReceivedDTO implements BetterDTOContract, JsonSerializable
{
    public function __construct(
        public string $mailUuid,
        public ResendEventsEnum $event,
        public ?array $payload,
    ) {}

    public static function fromWebhook(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailSent(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailDelivered(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailDeliveredDelayed(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailComplained(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailBounced(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailOpened(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailClicked(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailRecieved(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public static function fromEmailFailed(array $dto): self
    {
        $mailUuid = $dto['data']['headers'][0]['value'] ?? $dto['mailUuid'];

        return new self(
            mailUuid: $mailUuid,
            event: ResendEventsEnum::tryFrom($dto['type']),
            payload: $dto,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'mailUuid' => $this->mailUuid,
            'type' => $this->event->value,
            'payload' => $this->payload,
        ];
    }
}
