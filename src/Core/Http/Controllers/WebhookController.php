<?php

namespace Basement\BetterMails\Core\Http\Controllers;

use Basement\BetterMails\Core\Contracts\BetterDriverContract;
use Basement\BetterMails\Core\Contracts\BetterDTOContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class WebhookController
{
    public function __invoke(Request $request, BetterDriverContract $driver)
    {
        $driver->handle($request->all());
    }
}
