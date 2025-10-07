<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\HardBouncedEventContract;

final class HardBouncedMailListener
{
    use HasMail;

    public function handle(HardBouncedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->hardBounced();
    }
}
