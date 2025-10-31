<?php

namespace Basement\BetterMails\Resend\Email\Events;

use Basement\BetterMails\Resend\Email\DTOs\ResendWebhookReceivedMailDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ResendEmailDeliveryDelayedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly ResendWebhookReceivedMailDTO $dto,
    ) {}
}
