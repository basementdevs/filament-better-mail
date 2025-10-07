<?php

namespace Basement\BetterMails\Resend\Email\Events;

use Basement\BetterMails\Core\Contracts\External\RecievedEventContract;
use Basement\BetterMails\Resend\Email\DTOs\ResendWebhookReceivedDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ResendEmailReceivedEvent implements RecievedEventContract
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly ResendWebhookReceivedDTO $dto,
    ) {}
}
