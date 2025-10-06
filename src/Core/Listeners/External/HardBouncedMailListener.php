<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Contracts\External\ClickedEventContract;
use Basement\BetterMails\Core\Contracts\External\HardBouncedEventContract;
use Basement\BetterMails\Core\Models\BetterEmail;

class HardBouncedMailListener
{
    public function handle(HardBouncedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->hardBounced();
    }

    private function findMail(string $uuid): BetterEmail
    {
        return BetterEmail::query()->where('uuid', $uuid)->firstOrFail();
    }
}
