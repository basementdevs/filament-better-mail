<?php

namespace Basement\BetterMails\Resend\Email\Middleware;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Http\Middleware\AbstractMailMiddleware;
use Basement\BetterMails\Core\Support\BetterMailLogger;
use Closure;
use Illuminate\Http\Request;
use Resend\Laravel\Http\Middleware\VerifyWebhookSignature;

final class VerifyWebhookSignatureAdapter extends AbstractMailMiddleware implements BetterMiddlewareContract
{
    public function handle(Request $request, Closure $next): mixed
    {
        BetterMailLogger::info('Verifying webhook signature.', ['provider' => 'resend']);

        $middleware = new VerifyWebhookSignature;
        $result = $middleware->handle($request, $next);

        BetterMailLogger::info('Webhook signature verified.', ['provider' => 'resend']);

        return $result;
    }
}
