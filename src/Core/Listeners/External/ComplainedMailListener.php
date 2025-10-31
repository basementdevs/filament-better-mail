<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\ComplainedEventContract;
use Basement\BetterMails\Core\Enums\MailEventTypeEnum;

final class ComplainedMailListener
{
    use HasMail;

    public function handle(ComplainedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->id);

        $mail->complained();

        $mail->events()->create([
            'type' => MailEventTypeEnum::Complained,
            'occurred_at' => now(),
        ]);
    }
}
