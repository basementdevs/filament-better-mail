<?php

namespace Basement\BetterMails\Tests\Fixtures\Resend;

use Basement\BetterMails\Resend\Email\ResendEventsEnum;

class ResendWebhookDataProvider
{
    public static function withMailEvent(string $uuid, ResendEventsEnum $event): array
    {
        return [
            'data' => [
                'created_at' => '2025-10-05 23:59:51.98696+00',
                'email_id' => 'b5482019-eef5-4afd-89ae-b93acb1dda7f',
                'from' => '"Laravel" <onboarding@resend.dev>',
                'headers' => [
                    [
                        'name' => config('filament-better-mails.mails.headers.key'),
                        'value' => $uuid,
                    ],
                ],
                'subject' => 'Fuedase Mail',
                'to' => [
                    'delivered@resend.dev',
                ]
            ],
            'type' => $event->value,
        ];
    }
}
