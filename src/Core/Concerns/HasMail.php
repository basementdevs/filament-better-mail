<?php

namespace Basement\BetterMails\Core\Concerns;

use Basement\BetterMails\Core\Models\BetterEmail;

trait HasMail
{
    public function findMail(string $uuid): BetterEmail
    {
        return BetterEmail::query()->where('uuid', $uuid)->firstOrFail();
    }
}
