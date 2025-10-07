<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\ClickedEventContract;

final class ClickedMailListener
{
    use HasMail;
    public function handle(ClickedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->clicked();
    }
}
