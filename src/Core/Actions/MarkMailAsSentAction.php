<?php

namespace Basement\BetterMails\Core\Actions;

use Basement\BetterMails\Core\Enums\MailEventType;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;

class MarkMailAsSentAction
{
    public static function execute(BetterEmail $email): void
    {
        $email->sent();

        BetterEmailEvent::query()->create([
            'mail_id' => $email->getKey(),
            'type' => MailEventType::Sent,
        ]);
    }
}
