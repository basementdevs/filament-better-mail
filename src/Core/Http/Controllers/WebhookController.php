<?php

namespace Basement\BetterMails\Core\Http\Controllers;

use Basement\BetterMails\Core\Contracts\BetterDriverContract;
use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controller;

final class WebhookController extends Controller
{
    public function __invoke(Request $request, BetterDriverContract $driver, string $provider)
    {
        $provider = SupportedMailProvidersEnum::tryFrom($provider);

        (new Pipeline(app()))
            ->send($request)
            ->through($provider->getMiddleware())
            ->thenReturn();

        $driver->handle($request->all());
    }
}
