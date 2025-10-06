<?php

namespace Basement\BetterMails\Tests\Fixtures\Resend;

class ResendWebhookDataProvider
{
    public static function mailSent(string $uuid): array
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
            'type' => 'email.delivered',
        ];
    }
}
