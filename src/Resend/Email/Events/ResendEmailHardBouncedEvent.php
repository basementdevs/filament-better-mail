<?php

namespace Basement\BetterMails\Resend\Email\Events;

use Basement\BetterMails\Core\Contracts\External\HardBouncedEventContract;
use Basement\BetterMails\Resend\Email\DTOs\ResendWebhookReceivedDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ResendEmailHardBouncedEvent implements HardBouncedEventContract
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly ResendWebhookReceivedDTO $dto,
    ) {}
}
