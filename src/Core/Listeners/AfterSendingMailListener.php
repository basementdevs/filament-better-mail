<?php

namespace Basement\BetterMails\Core\Listeners;

use Basement\BetterMails\Core\Actions\MarkMailAsSentAction;
use Basement\BetterMails\Core\Models\BetterEmail;
use Illuminate\Mail\Events\MessageSent;

class AfterSendingMailListener
{
    public function handle(MessageSent $event): void
    {
        $header = $event->message->getHeaders()->get(config('filament-better-mails.mails.headers.key'));
        if (! $header) {
            return;
        }

        $uuid = $header->getBody();
        $mail = BetterEmail::query()->where('uuid', $uuid)->first();
        if (! $mail) {
            return;
        }

        MarkMailAsSentAction::execute($mail);
    }
}
