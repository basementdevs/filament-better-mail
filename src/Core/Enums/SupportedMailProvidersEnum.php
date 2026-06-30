<?php

namespace Basement\BetterMails\Core\Enums;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Resend\Email\Middleware\FilterAllowedSenders;
use Basement\BetterMails\Resend\Email\Middleware\VerifyHeaderWebhookSignature;
use Basement\BetterMails\Resend\Email\Middleware\VerifyWebhookSignatureAdapter;

enum SupportedMailProvidersEnum: string
{
    case Resend = 'resend';

    /**
     * @return BetterMiddlewareContract[]
     */
    public function getMiddleware(): array
    {
        return match ($this) {
            self::Resend => [VerifyWebhookSignatureAdapter::class, FilterAllowedSenders::class, VerifyHeaderWebhookSignature::class],
        };
    }
}
