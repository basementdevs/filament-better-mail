<?php

namespace Basement\BetterMails\Core\Enums;

use Basement\BetterMails\Resend\Email\Middleware\VerifyResendWebhookSignature;
use Resend\Laravel\Http\Middleware\VerifyWebhookSignature;

enum SupportedMailProvidersEnum: string
{
    case Resend = 'resend';

    public function getMiddleware(): array
    {
        return match ($this) {
            self::Resend => [VerifyWebhookSignature::class, VerifyResendWebhookSignature::class],
        };
    }
}
