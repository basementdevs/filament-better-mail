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
            ResendEventsEnum::EmailSent => ResendEmailSentEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailSent($dto->jsonSerialize())),
            ResendEventsEnum::EmailDelivered => ResendEmailDeliveredEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailDelivered($dto->jsonSerialize())),
            ResendEventsEnum::EmailDeliveryDelayed => ResendEmailDeliveryDelayedEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailDeliveredDelayed($dto->jsonSerialize())),
            ResendEventsEnum::EmailComplained => ResendEmailComplainedEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailComplained($dto->jsonSerialize())),
            ResendEventsEnum::EmailBounced => ResendEmailHardBouncedEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailBounced($dto->jsonSerialize())),
            ResendEventsEnum::EmailOpened => ResendEmailOpenedEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailOpened($dto->jsonSerialize())),
            ResendEventsEnum::EmailClicked => ResendEmailClickedEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailClicked($dto->jsonSerialize())),
            ResendEventsEnum::EmailReceived => ResendEmailReceivedEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailRecieved($dto->jsonSerialize())),
            ResendEventsEnum::EmailFailed => ResendEmailFailedEvent::dispatch(ResendWebhookReceivedMailDTO::fromEmailFailed($dto->jsonSerialize())),
        };
    }
}
