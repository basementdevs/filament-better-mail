<?php

namespace Basement\BetterMails\Core\Contracts;

interface BetterDriverContract
{
    public function handle(array $data): void;
}
