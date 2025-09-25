<?php

namespace Basement\BetterMails\Core\Listeners;

use Basement\BetterMails\Core\Actions\MarkMailAsSentAction;
use Basement\BetterMails\Core\Models\BetterEmail;
use Illuminate\Mail\Events\MessageSent;

class AfterSendingMailListener
{
    public function handle(MessageSent $event): void
    {
        $uuid = $event->message->getHeaders()->get('X-Better-Mails-Event-Id')->getBody();

        $mail = BetterEmail::query()->where('uuid', $uuid)->first();
        MarkMailAsSentAction::execute($mail);
    }
}
