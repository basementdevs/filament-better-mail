<?php

namespace Basement\BetterMails\Core;

use Basement\BetterMails\Core\Enums\SupportedMailProviders;
use Illuminate\Support\Manager;

class BetterMailManager extends Manager
{
    public function createResendDriver(): void
    {
        // TODO: Implement Resend Driver.
    }

    public function getDefaultDriver(): string
    {
        return SupportedMailProviders::Resend->value;
    }
}
