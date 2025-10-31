<?php

namespace Basement\BetterMails\Core\Http\Controllers;

use Basement\BetterMails\Core\Contracts\BetterDriverContract;
use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Pipeline;

final class WebhookController extends Controller
{
    public function __invoke(Request $request, BetterDriverContract $driver, string $provider)
    {
        $provider = SupportedMailProvidersEnum::tryFrom($provider);

        Pipeline::send($request)
            ->through($provider->getMiddleware())
            ->thenReturn();

        $driver->handle($request->all());
    }
}
