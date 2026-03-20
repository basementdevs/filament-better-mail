<?php

namespace Basement\BetterMails\Resend\Email\DTOs;

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

        $headerKey = config('filament-better-mails.mails.headers.key', 'X-Better-Mails-Event-ID');

        $mailUuid = collect($dto['data']['headers'] ?? [])
            ->firstWhere('name', $headerKey)['value'] ?? null;

        if ($mailUuid === null) {
            return null;
        }

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
