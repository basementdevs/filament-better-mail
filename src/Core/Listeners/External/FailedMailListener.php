<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Contracts\External\FailedEventContract;

final class FailedMailListener
{
    public function handle(FailedEventContract $event): void {}
}
