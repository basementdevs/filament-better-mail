<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\ClickedEventContract;
use Basement\BetterMails\Core\Enums\MailEventTypeEnum;

final class ClickedMailListener
{
    use HasMail;

    public function handle(ClickedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->id);

        $mail->clicked();

        $mail->events()->create([
            'type' => MailEventTypeEnum::Clicked,
            'occurred_at' => now(),
        ]);
    }
}
