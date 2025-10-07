<?php

namespace Basement\BetterMails\Core\Listeners\External;

use Basement\BetterMails\Core\Contracts\External\RecievedEventContract;

final class ReceivedMailListener
{
    public function handle(RecievedEventContract $event): void {}
}
