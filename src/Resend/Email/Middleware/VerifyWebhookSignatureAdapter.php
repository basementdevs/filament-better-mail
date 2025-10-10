<?php

namespace Basement\BetterMails\Resend\Email\Middleware;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Http\Middleware\AbstractMailMiddleware;
use Closure;
use Illuminate\Http\Request;
use Resend\Laravel\Http\Middleware\VerifyWebhookSignature;

final class VerifyWebhookSignatureAdapter extends AbstractMailMiddleware implements BetterMiddlewareContract
{
    public function handle(Request $request, Closure $next): mixed
    {
        $middleware = new VerifyWebhookSignature;

        return $middleware->handle($request, $next);
    }
}
