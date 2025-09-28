<?php

namespace Basement\BetterMails\Core;

use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Illuminate\Support\Manager;

class BetterMailManager extends Manager
{
    public function createResendDriver(): void
    {
        // TODO: Implement Resend Driver.
    }

    public function getDefaultDriver(): string
    {
        return SupportedMailProvidersEnum::Resend->value;
    }
}
