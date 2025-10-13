<?php

namespace Basement\BetterMails\Resend;

use Basement\BetterMails\Core\AbstractMailDriver;
use Basement\BetterMails\Core\Contracts\BetterDriverContract;
use Basement\BetterMails\Resend\Email\DTOs\ResendWebhookReceivedMailDTO;
use Basement\BetterMails\Resend\Email\Events\ResendEmailClickedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailComplainedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailDeliveredEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailDeliveryDelayedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailFailedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailHardBouncedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailOpenedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailReceivedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailSentEvent;
use Basement\BetterMails\Resend\Email\ResendEventsEnum;

final class ResendDriver extends AbstractMailDriver implements BetterDriverContract
{
    // TODO: implements mail() method

    // TODO: implement audience() method
    public function handle(array $data): void
    {
        $dto = ResendWebhookReceivedMailDTO::fromWebhook($data);
        match ($dto->event) {
            ResendEventsEnum::EmailSent => ResendEmailSentEvent::dispatch($dto),
            ResendEventsEnum::EmailDelivered => ResendEmailDeliveredEvent::dispatch($dto),
            ResendEventsEnum::EmailDeliveryDelayed => ResendEmailDeliveryDelayedEvent::dispatch($dto),
            ResendEventsEnum::EmailComplained => ResendEmailComplainedEvent::dispatch($dto),
            ResendEventsEnum::EmailBounced => ResendEmailHardBouncedEvent::dispatch($dto),
            ResendEventsEnum::EmailOpened => ResendEmailOpenedEvent::dispatch($dto),
            ResendEventsEnum::EmailClicked => ResendEmailClickedEvent::dispatch($dto),
            ResendEventsEnum::EmailReceived => ResendEmailReceivedEvent::dispatch($dto),
            ResendEventsEnum::EmailFailed => ResendEmailFailedEvent::dispatch($dto),
        };
    }
}
