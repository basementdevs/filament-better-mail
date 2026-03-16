<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\ScheduledEventContract;
use Basement\BetterMails\Core\Enums\MailEventTypeEnum;

final class ScheduledMailListener
{
    use HasMail;

    public function handle(ScheduledEventContract $event): void
    {
        $mail = $this->findMail($event->dto->id);

        $mail->scheduled();

        $mail->events()->create([
            'type' => MailEventTypeEnum::Scheduled,
            'occurred_at' => now(),
        ]);
    }
}
