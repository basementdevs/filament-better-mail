<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\OpenedEventContract;

final class OpenedMailListener
{
    use HasMail;

    public function handle(OpenedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->id);
        $mail->opened();
    }
}
