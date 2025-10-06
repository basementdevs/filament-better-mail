<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Contracts\External\ComplainedEventContract;
use Basement\BetterMails\Core\Models\BetterEmail;

class ComplainedMailListener
{
    public function handle(ComplainedEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->complained();
    }

    private function findMail(string $uuid): BetterEmail
    {
        return BetterEmail::query()->where('uuid', $uuid)->firstOrFail();
    }
}
