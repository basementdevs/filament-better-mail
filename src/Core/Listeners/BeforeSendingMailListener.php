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
                'subject' => $event->message->getSubject(),
                'html' => $event->message->getHtmlBody(),
                'text' => $event->message->getTextBody(),
                'from' => $event->message->getFrom()[0]->getAddress(),
                'to' => $event->message->getTo()[0]->getAddress(),
                'reply_to' => $event->message->getFrom()[0]->getAddress(),
                'cc' => $event->message->getCc()[0]->getAddress(),
                'bcc' => $event->message->getBcc()[0]->getAddress(),
                'mail_class' => $event->data['__laravel_mailable'],
                'transport' => SupportedMailProviders::Resend->value,
            ]),
        );

        $event->message->getHeaders()->addTextHeader('X-Better-Mails-Event-Id', $uuid);
    }
}
