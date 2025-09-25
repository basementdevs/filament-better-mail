<?php

namespace Basement\BetterMails\Core\Listeners;

use Basement\BetterMails\Core\Actions\CreateBetterMailAction;
use Basement\BetterMails\Core\DTOs\BetterMailDTO;
use Basement\BetterMails\Core\Enums\SupportedMailProviders;
use Illuminate\Mail\Events\MessageSending;
use Ramsey\Uuid\Uuid;

class BeforeSendingMailListener
{
    public function handle(MessageSending $event): void
    {
        $uuid = Uuid::uuid4();

        CreateBetterMailAction::execute(
            BetterMailDTO::make([
                'uuid' => $uuid,
                'mailer' => $event->data['mailer'],
                'subject' => $event->message->getSubject() ?? null,
                'html' => $event->message->getHtmlBody() ?? null,
                'text' => $event->message->getTextBody() ?? null,
                'from' => $event->message->getFrom()[0]->getAddress() ?? null,
                'to' => $event->message->getTo()[0]->getAddress() ?? null,
                'reply_to' => $event->message->getFrom()[0]->getAddress() ?? null,
                'cc' => $event->message->getCc()[0]->getAddress() ?? null,
                'bcc' => $event->message->getBcc()[0]->getAddress() ?? null,
                'mail_class' => $event->data['__laravel_mailable'] ?? null,
                'transport' => SupportedMailProviders::Resend->value,
            ]),
        );

        $event->message->getHeaders()->addTextHeader('X-Better-Mails-Event-Id', $uuid);
    }
}
