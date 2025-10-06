<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Contracts\External\DeliveredEventContract;
use Basement\BetterMails\Core\Models\BetterEmail;

final class DeliveredMailListener
{
    public function handle(DeliveredEventContract $event): void
    {
        $mail = $this->findMail($event->dto->mailUuid);
        $mail->delivered();
    }

    private function findMail(string $uuid): BetterEmail
    {
        return BetterEmail::query()->where('uuid', $uuid)->firstOrFail();
    }
}
