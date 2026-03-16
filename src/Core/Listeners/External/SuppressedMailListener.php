<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\SuppressedEventContract;
use Basement\BetterMails\Core\Enums\MailEventTypeEnum;

final class SuppressedMailListener
{
    use HasMail;

    public function handle(SuppressedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->id);

        $mail->suppressed();

        $mail->events()->create([
            'type' => MailEventTypeEnum::Suppressed,
            'occurred_at' => now(),
        ]);
    }
}
