<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Concerns\HasMail;
use Basement\BetterMails\Core\Contracts\External\DeliveredEventContract;

final class DeliveredMailListener
{
    use HasMail;
    public function handle(DeliveredEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->delivered();
    }
}
