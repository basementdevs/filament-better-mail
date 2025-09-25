<?php

namespace Basement\BetterMails\Core\Listeners;

use Basement\BetterMails\Core\Enums\MailEventType;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Illuminate\Mail\Events\MessageSent;

class AfterSendingMailListener
{
    public function handle(MessageSent $event): void
    {
        $uuid = $event->message->getHeaders()->get('X-Better-Mails-Event-Id')->getBody();

        $mail = BetterEmail::query()->where('uuid', $uuid)->first();
        $mail->sent();

        $mailEvent = BetterEmailEvent::query()->create([
            'mail_id' => $mail->getKey(),
            'type' => MailEventType::Sent,
        ]);
    }
}
