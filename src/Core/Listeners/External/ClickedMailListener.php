<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Contracts\External\ClickedEventContract;
use Basement\BetterMails\Core\Models\BetterEmail;

class ClickedMailListener
{
    public function handle(ClickedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->clicked();
    }

    private function findMail(string $uuid): BetterEmail
    {
        return BetterEmail::query()->where('uuid', $uuid)->firstOrFail();
    }
}
