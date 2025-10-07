<?php

namespace Basement\BetterMails\Resend\Email\Middleware;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Exceptions\MailException;
use Basement\BetterMails\Core\Http\Middlewares\AbstractMailMiddleware;
use Closure;
use Illuminate\Http\Request;

class VerifyResendWebhookSignature extends AbstractMailMiddleware implements BetterMiddlewareContract
{
    /**
     * @throws MailException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $headers = $request->input('data.headers');

        parent::validateHeaderKey($headers, 'name', 'value');

        // implementar a secret key colocar no env e config do proprio resend
        // config('filament-better-mails.webhooks.drivers.resend.key_secret');
        return $next($request);
    }
}
