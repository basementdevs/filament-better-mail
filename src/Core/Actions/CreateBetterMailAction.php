<?php

namespace Basement\BetterMails\Core\Actions;

use Basement\BetterMails\Core\DTOs\BetterMailDTO;
use Basement\BetterMails\Core\Models\BetterEmail;

final class CreateBetterMailAction
{
    public static function execute(BetterMailDTO $dto): void
    {
        BetterEmail::query()->create([
            'uuid' => $dto->uuid,
            'mailer' => $dto->mailer,
            'subject' => $dto->subject,
            'html' => $dto->html,
            'text' => $dto->text,
            'from' => $dto->from,
            'to' => $dto->to,
            'reply_to' => $dto->reply_to,
            'cc' => $dto->cc,
            'bcc' => $dto->bcc,
            'mail_class' => $dto->mail_class,
            'transport' => $dto->transport,
        ]);
    }
}
