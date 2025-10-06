<?php

namespace Basement\BetterMails\Resend;

use Basement\BetterMails\Core\AbstractMailDriver;
use Basement\BetterMails\Core\Contracts\BetterDriverContract;
use Basement\BetterMails\Core\Contracts\BetterDTOContract;
use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Resend\Email\DTOs\ResendWebhookReceivedDTO;
use Basement\BetterMails\Resend\Email\ResendEventsEnum;

final class ResendDriver extends AbstractMailDriver implements BetterDriverContract
{
    // TODO: implements mail() method

    // TODO: implement audience() method
    public function handle(array $data): void
    {
        $dto = ResendWebhookReceivedDTO::fromWebhook($data);
        $mail = $this->findMail($dto->mailUuid);

        match ($dto->event) {
            ResendEventsEnum::Email_Sent => null,
            ResendEventsEnum::Email_Delivered => $this->mailDelivered($mail),
            ResendEventsEnum::Email_Delivery_Delayed => throw new \Exception('To be implemented'),
            ResendEventsEnum::Email_Complained => throw new \Exception('To be implemented'),
            ResendEventsEnum::Email_Bounced => throw new \Exception('To be implemented'),
            ResendEventsEnum::Email_Opened => throw new \Exception('To be implemented'),
            ResendEventsEnum::Email_Clicked => throw new \Exception('To be implemented'),
            ResendEventsEnum::Email_Received => throw new \Exception('To be implemented'),
            ResendEventsEnum::Email_Failed => throw new \Exception('To be implemented'),
        };
    }

    private function mailDelivered(BetterEmail $mail): void
    {
        $mail->delivered();
    }

    private function findMail(string $mailUuid): BetterEmail
    {
        return BetterEmail::query()
            ->where('transport', SupportedMailProvidersEnum::Resend)
            ->where('uuid', $mailUuid)->firstOrFail();
    }
}
