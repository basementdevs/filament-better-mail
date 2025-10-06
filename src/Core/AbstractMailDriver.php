<?php

namespace Basement\BetterMails\Core;

use Basement\BetterMails\Core\Contracts\BetterDriverContract;

abstract class AbstractMailDriver implements BetterDriverContract
{
    abstract public function handle(array $data): void;
}
