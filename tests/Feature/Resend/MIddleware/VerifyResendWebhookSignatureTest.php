<?php

use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Basement\BetterMails\Core\Exceptions\MailException;
use Basement\BetterMails\Resend\Email\Middleware\VerifyWebhookSignatureAdapter;
use Illuminate\Support\Facades\Log;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

it('should throw exception if mail header was not sent', function (): void {
    $this->withoutMiddleware(VerifyWebhookSignatureAdapter::class);
    withoutExceptionHandling();
    postJson(route('filament-better-mails.webhook.store', ['provider' => SupportedMailProvidersEnum::Resend]), [
        'data' => [
            'created_at' => '2025-10-05 23:59:51.98696+00',
            'email_id' => 'b5482019-eef5-4afd-89ae-b93acb1dda7f',
            'from' => '"Laravel" <onboarding@resend.dev>',
            'headers' => [
                [
                    'name' => 'wrong_header',
                    'value' => 'no_one',
                ],
            ],
            'subject' => 'Fuedase Mail',
            'to' => [
                'delivered@resend.dev',
            ]
        ],
        'type' => 'email.delivered',
    ]);

})->throws(
    MailException::class,
    'Uuid mail signature not found on body request',
    403
);

it('should return 200 and log warning when data.headers is null', function (): void {
    $this->withoutMiddleware(VerifyWebhookSignatureAdapter::class);

    Log::shouldReceive('warning')->once();
    Log::shouldReceive('info')->zeroOrMoreTimes();

    $response = postJson(
        route('filament-better-mails.webhook.store', ['provider' => SupportedMailProvidersEnum::Resend]),
        [
            'data' => [
                'created_at' => '2025-10-05 23:59:51.98696+00',
                'email_id' => 'b5482019-eef5-4afd-89ae-b93acb1dda7f',
                'from' => '"Laravel" <onboarding@resend.dev>',
                'subject' => 'Test Mail',
                'to' => ['delivered@resend.dev'],
            ],
            'type' => 'email.delivered',
        ]
    );

    $response->assertOk();
});

it('should return 200 and log warning when data is missing entirely', function (): void {
    $this->withoutMiddleware(VerifyWebhookSignatureAdapter::class);

    Log::shouldReceive('warning')->once();
    Log::shouldReceive('info')->zeroOrMoreTimes();

    $response = postJson(
        route('filament-better-mails.webhook.store', ['provider' => SupportedMailProvidersEnum::Resend]),
        [
            'type' => 'email.delivered',
        ]
    );

    $response->assertOk();
});
