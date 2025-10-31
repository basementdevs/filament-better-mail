<?php

namespace Basement\BetterMails\Core\Http\Middleware;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Exceptions\MailException;
use Closure;
use Illuminate\Http\Request;

abstract class AbstractMailMiddleware implements BetterMiddlewareContract
{
    abstract public function handle(Request $request, Closure $next): mixed;

    /**
     * @throws MailException
     */
    public function validateHeaderKey(array $headers, string $headerKey, string $defaultKey = 'value'): void
    {
        $mailUuid = null;

        foreach ($headers as $header) {

            if (strtolower($header[$headerKey]) == strtolower(config('filament-better-mails.mails.headers.key'))) {
                $mailUuid = $header[$defaultKey];
                break;
            }
        }
        if (! $mailUuid) {
            throw MailException::missingUuidHeader('Uuid mail signature not found on body request');
        }
    }
}
