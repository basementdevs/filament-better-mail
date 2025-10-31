<?php

namespace Basement\BetterMails\Resend\Email\Middleware;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Exceptions\MailException;
use Basement\BetterMails\Core\Http\Middleware\AbstractMailMiddleware;
use Closure;
use Illuminate\Http\Request;

class VerifyHeaderWebhookSignature extends AbstractMailMiddleware implements BetterMiddlewareContract
{
    /**
     * @throws MailException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $headers = $request->input('data.headers');

        parent::validateHeaderKey($headers, 'name', 'value');

        return $next($request);
    }
}
