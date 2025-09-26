<?php

namespace Basement\BetterMails\Core\Actions;

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Models\BetterEmail;

class MarkMailAsSentAction
{
    public static function execute(BetterEmail $email): void
    {
        $email->sent();

        $email->events()->create([
            'mail_id' => $email->getKey(),
            'type' => MailEventTypeEnum::Sent,
        ]);
    }
}
