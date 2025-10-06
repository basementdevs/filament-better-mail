<?php

namespace Basement\BetterMails\Core\Contracts;

interface BetterDTOContract
{
    public static function fromWebhook(array $dto): self;
}
