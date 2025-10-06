<?php

namespace Basement\BetterMails\Resend\Email\Events;

use Basement\BetterMails\Core\Contracts\External\DeliveredEventContract;
use Basement\BetterMails\Resend\Email\DTOs\ResendWebhookReceivedDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ResendEmailDeliveredEvent implements DeliveredEventContract
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly ResendWebhookReceivedDTO $dto,
    ) {}
}
