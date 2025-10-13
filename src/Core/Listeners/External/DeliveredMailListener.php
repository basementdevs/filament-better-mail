<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\DeliveredEventContract;
use Basement\BetterMails\Core\Enums\MailEventTypeEnum;

final class DeliveredMailListener
{
    use HasMail;

    public function handle(DeliveredEventContract $event): void
    {
        $mail = $this->findMail($event->dto->id);

        $mail->delivered();

        $mail->events()->create([
            'type' => MailEventTypeEnum::Delivered,
            'occurred_at' => now(),
        ]);
    }
}
