<?php

namespace Basement\BetterMails\Core\Http\Controllers;

use Basement\BetterMails\Core\Contracts\BetterDriverContract;
use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Basement\BetterMails\Core\Support\BetterMailLogger;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Pipeline;
use Symfony\Component\HttpFoundation\Response;

final class WebhookController extends Controller
{
    public function __invoke(Request $request, BetterDriverContract $driver, string $provider)
    {
        $provider = SupportedMailProvidersEnum::tryFrom($provider);
        if (! $provider) {
            return response()->json(['message' => 'Unsupported provider.'], 422);
        }

        BetterMailLogger::info('Webhook received.', [
            'provider' => $provider?->value,
            'type' => $request->input('type'),
            'request_headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        $result = Pipeline::send($request)
            ->through($provider->getMiddleware())
            ->thenReturn();

        if ($result instanceof Response) {
            return $result;
        }

        $driver->handle($request->all());

        BetterMailLogger::info('Webhook processed successfully.', [
            'provider' => $provider?->value,
            'type' => $request->input('type'),
        ]);

        return response()->json(['message' => 'Webhook processed.'], 200);
    }
}
