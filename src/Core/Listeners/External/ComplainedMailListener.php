<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\ComplainedEventContract;

final class ComplainedMailListener
{
    use HasMail;

    public function handle(ComplainedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->complained();
    }
}
