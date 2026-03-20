<?php

namespace Basement\BetterMails\Resend\Email\Middleware;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Exceptions\MailException;
use Basement\BetterMails\Core\Http\Middleware\AbstractMailMiddleware;
use Basement\BetterMails\Core\Support\BetterMailLogger;
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

        if (! is_array($headers)) {
            BetterMailLogger::warning('Webhook received without email headers, skipping.', [
                'type' => $request->input('type'),
                'email_id' => $request->input('data.email_id'),
                'request_headers' => $request->headers->all(),
                'payload' => $request->all(),
            ]);

            return response()->json(['message' => 'Webhook received, but no trackable headers found.'], 200);
        }

        parent::validateHeaderKey($headers, 'name', 'value');

        BetterMailLogger::info('Email header validation passed.', [
            'type' => $request->input('type'),
            'request_headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        return $next($request);
    }
}
