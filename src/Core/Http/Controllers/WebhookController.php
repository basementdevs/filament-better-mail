<?php

namespace Basement\BetterMails\Core\Http\Controllers;

use Basement\BetterMails\Core\Contracts\BetterDriverContract;
use Illuminate\Http\Request;

final class WebhookController
{
    public function __invoke(Request $request, BetterDriverContract $driver)
    {
        $driver->handle($request->all());
    }
}
