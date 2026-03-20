<?php

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Basement\BetterMails\Resend\Email\Middleware\VerifyWebhookSignatureAdapter;
use Basement\BetterMails\Resend\Email\ResendEventsEnum;
use Basement\BetterMails\Tests\Fixtures\Resend\ResendWebhookDataProvider;
use Illuminate\Support\Facades\Log;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    withoutExceptionHandling();
    $this->uuid = '6e289259-7918-483a-97d1-78f05dadca13';
    $this->mail = BetterEmail::factory()
        ->has(BetterEmailEvent::factory(), 'events')
        ->withDriver(SupportedMailProvidersEnum::Resend)
        ->create([
            'uuid' => $this->uuid,
        ]);
    $this->withoutMiddleware(VerifyWebhookSignatureAdapter::class);
});

it('should be able to update the emails status to delivered via webhook', function (): void {
    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        ResendWebhookDataProvider::withMailEvent(
            uuid: $this->uuid,
            event: ResendEventsEnum::EmailDelivered
        )
    );

    $response->assertOk();

    $this->mail->refresh();

    expect($this->mail->events->first()->type->value)
        ->toBe(MailEventTypeEnum::Delivered->value);

    assertDatabaseHas(BetterEmail::class, [
        'uuid' => $this->mail->uuid,
        'delivered_at' => now(),
    ]);
});

it('should be able to update email status to complained', function () {
    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        ResendWebhookDataProvider::withMailEvent(
            uuid: $this->uuid,
            event: ResendEventsEnum::EmailComplained
        )
    );

    $response->assertOk();

    $this->mail->refresh();

    expect($this->mail->events->first()->type->value)
        ->toBe(MailEventTypeEnum::Complained->value);

    assertDatabaseHas(BetterEmail::class, [
        'uuid' => $this->mail->uuid,
        'complained_at' => now(),
    ]);

    assertDatabaseHas(BetterEmailEvent::class, [
        'occurred_at' => now(),
    ]);
});
it('should be able to update email status to clicked', function () {
    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        ResendWebhookDataProvider::withMailEvent(
            uuid: $this->uuid,
            event: ResendEventsEnum::EmailClicked
        )
    );

    $response->assertOk();

    $this->mail->refresh();

    expect($this->mail->events->first()->type->value)
        ->toBe(MailEventTypeEnum::Clicked->value);

    assertDatabaseHas(BetterEmail::class, [
        'uuid' => $this->mail->uuid,
        'clicks' => 1,
        'last_clicked_at' => now(),
    ]);

    assertDatabaseHas(BetterEmailEvent::class, [
        'occurred_at' => now(),
    ]);
});
it('should be able to update email status to opened', function () {
    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        ResendWebhookDataProvider::withMailEvent(
            uuid: $this->uuid,
            event: ResendEventsEnum::EmailOpened
        )
    );

    $response->assertOk();

    $this->mail->refresh();

    expect($this->mail->events->first()->type->value)
        ->toBe(MailEventTypeEnum::Opened->value);

    assertDatabaseHas(BetterEmail::class, [
        'uuid' => $this->mail->uuid,
        'opens' => 1,
        'last_opened_at' => now(),
    ]);

    assertDatabaseHas(BetterEmailEvent::class, [
        'occurred_at' => now(),
    ]);
});

it('should be able to update email status to bounced', function () {
    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        ResendWebhookDataProvider::withMailEvent(
            uuid: $this->uuid,
            event: ResendEventsEnum::EmailBounced
        )
    );

    $response->assertOk();

    $this->mail->refresh();

    expect($this->mail->events->first()->type->value)
        ->toBe(MailEventTypeEnum::HardBounced->value);

    assertDatabaseHas(BetterEmail::class, [
        'uuid' => $this->mail->uuid,
        'hard_bounced_at' => now(),
    ]);

    assertDatabaseHas(BetterEmailEvent::class, [
        'occurred_at' => now(),
    ]);
});

it('should return 200 and log warning for unknown event type', function () {
    Log::shouldReceive('info')->zeroOrMoreTimes();
    Log::shouldReceive('warning')
        ->once()
        ->with('BetterMails: Received unknown Resend webhook event type, skipping.', [
            'type' => 'email.foobar',
        ]);

    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        [
            'data' => [
                'created_at' => '2025-10-05 23:59:51.98696+00',
                'email_id' => 'b5482019-eef5-4afd-89ae-b93acb1dda7f',
                'from' => '"Laravel" <onboarding@resend.dev>',
                'headers' => [
                    [
                        'name' => config('filament-better-mails.mails.headers.key'),
                        'value' => $this->uuid,
                    ],
                ],
                'subject' => 'Test Mail',
                'to' => ['delivered@resend.dev'],
            ],
            'type' => 'email.foobar',
        ]
    );

    $response->assertOk();
});

it('should return 200 and log warning when type key is missing', function () {
    Log::shouldReceive('info')->zeroOrMoreTimes();
    Log::shouldReceive('warning')
        ->once()
        ->with('BetterMails: Received unknown Resend webhook event type, skipping.', [
            'type' => null,
        ]);

    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        [
            'data' => [
                'created_at' => '2025-10-05 23:59:51.98696+00',
                'email_id' => 'b5482019-eef5-4afd-89ae-b93acb1dda7f',
                'from' => '"Laravel" <onboarding@resend.dev>',
                'headers' => [
                    [
                        'name' => config('filament-better-mails.mails.headers.key'),
                        'value' => $this->uuid,
                    ],
                ],
                'subject' => 'Test Mail',
                'to' => ['delivered@resend.dev'],
            ],
        ]
    );

    $response->assertOk();
});

it('should be able to update email status to scheduled via webhook', function () {
    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        ResendWebhookDataProvider::withMailEvent(
            uuid: $this->uuid,
            event: ResendEventsEnum::EmailScheduled
        )
    );

    $response->assertOk();

    $this->mail->refresh();

    expect($this->mail->events->first()->type->value)
        ->toBe(MailEventTypeEnum::Scheduled->value);

    assertDatabaseHas(BetterEmail::class, [
        'uuid' => $this->mail->uuid,
        'scheduled_at' => now(),
    ]);

    assertDatabaseHas(BetterEmailEvent::class, [
        'occurred_at' => now(),
    ]);
});

it('should be able to update email status to suppressed via webhook', function () {
    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        ResendWebhookDataProvider::withMailEvent(
            uuid: $this->uuid,
            event: ResendEventsEnum::EmailSuppressed
        )
    );

    $response->assertOk();

    $this->mail->refresh();

    expect($this->mail->events->first()->type->value)
        ->toBe(MailEventTypeEnum::Suppressed->value);

    assertDatabaseHas(BetterEmail::class, [
        'uuid' => $this->mail->uuid,
        'suppressed_at' => now(),
    ]);

    assertDatabaseHas(BetterEmailEvent::class, [
        'occurred_at' => now(),
    ]);
});
