<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Contracts\External\OpenedEventContract;
use Basement\BetterMails\Core\Models\BetterEmail;

class OpenedMailListener
{
    public function handle(OpenedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->opened();
    }

    private function findMail(string $uuid): BetterEmail
    {
        return BetterEmail::query()->where('uuid', $uuid)->firstOrFail();
    }
}
