<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\HardBouncedEventContract;
use Basement\BetterMails\Core\Enums\MailEventTypeEnum;

final class HardBouncedMailListener
{
    use HasMail;

    public function handle(HardBouncedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->id);

        $mail->hardBounced();

        $mail->events()->create([
            'type' => MailEventTypeEnum::HardBounced,
            'occurred_at' => now(),
        ]);
    }
}
