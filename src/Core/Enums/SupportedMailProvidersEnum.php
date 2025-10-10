<?php

namespace Basement\BetterMails\Core\Enums;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Resend\Email\Middleware\VerifyWebhookSignatureAdapter;
use Basement\BetterMails\Resend\Email\Middleware\VerifyResendWebhookSignature;

enum SupportedMailProvidersEnum: string
{
    case Resend = 'resend';

    /**
     * @return BetterMiddlewareContract[]
     */
    public function getMiddleware(): array
    {
        return match ($this) {
            self::Resend => [VerifyWebhookSignatureAdapter::class, VerifyResendWebhookSignature::class],
        };
    }
}
