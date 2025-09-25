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
            'from' => array_map(fn ($from) => is_string($from) ? $from : $from->getAddress(), $dto->from),
            'to' => array_map(fn ($to) => is_string($to) ? $to : $to->getAddress(), $dto->to),
            'reply_to' => array_map(fn ($reply_to) => is_string($reply_to) ? $reply_to : $reply_to->getAddress(), $dto->reply_to),
            'cc' => array_map(fn ($cc) => is_string($cc) ? $cc : $cc->getAddress(), $dto->cc),
            'bcc' => array_map(fn ($bcc) => is_string($bcc) ? $bcc : $bcc->getAddress(), $dto->bcc),
            'mail_class' => $dto->mail_class,
            'transport' => $dto->transport,
        ]);
    }
}
