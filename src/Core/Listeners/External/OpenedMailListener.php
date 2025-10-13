<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\OpenedEventContract;
use Basement\BetterMails\Core\Enums\MailEventTypeEnum;

final class OpenedMailListener
{
    use HasMail;

    public function handle(OpenedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->id);

        $mail->opened();

        $mail->events()->create([
            'type' => MailEventTypeEnum::Opened,
            'occurred_at' => now(),
        ]);
    }
}
