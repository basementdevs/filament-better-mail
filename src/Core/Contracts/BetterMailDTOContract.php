<?php

namespace Basement\BetterMails\Core\Contracts;

interface BetterMailDTOContract
{
    public static function fromWebhook(array $dto): self;
}
