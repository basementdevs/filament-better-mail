<?php

namespace Basement\BetterMails\Resend\Email\Middleware;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Tests\Feature\Resend\Exceptions\ResendException;
use Closure;
use Illuminate\Http\Request;

class VerifyResendWebhookSignature implements BetterMiddlewareContract
{
    /**
     * @throws ResendException
     */
    public function handle(Request $request, Closure $next)
    {
        $headers = $request->input('data.headers');

        $mailUuid = null;

        foreach ($headers as $header) {

            if ($header['name'] == config('filament-better-mails.mails.headers.key')) {
                $mailUuid = $header['value'];
                break;
            }
        }
        if (! $mailUuid) {
            throw ResendException::missingUuidHeader('Uuid mail signature not found on body request');
        }

        // implementar a secret key colocar no env e config do proprio resend
        // config('filament-better-mails.webhooks.drivers.resend.key_secret');
        return $next($request);
    }
}
