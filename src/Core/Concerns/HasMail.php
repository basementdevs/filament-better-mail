<?php

namespace Basement\BetterMails\Core\Concerns;

trait HasMail
{
    public function findMail(string $uuid)
    {
        $model = config('filament-better-mails.mails.models.mail');

        return $model::query()->where('uuid', $uuid)->firstOrFail();
    }
}
