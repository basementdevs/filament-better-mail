<?php

namespace Basement\BetterMails\Resend\Email\Events;

use Basement\BetterMails\Core\Contracts\External\ComplainedEventContract;
use Basement\BetterMails\Resend\Email\DTOs\ResendWebhookReceivedDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ResendEmailComplainedEvent implements COmplainedEventContract
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly ResendWebhookReceivedDTO $dto,
    ) {}
}
