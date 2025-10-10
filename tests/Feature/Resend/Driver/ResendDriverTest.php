<?php

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Basement\BetterMails\Resend\Email\Middleware\VerifyWebhookSignatureAdapter;
use Basement\BetterMails\Resend\Email\ResendEventsEnum;
use Basement\BetterMails\Tests\Fixtures\Resend\ResendWebhookDataProvider;

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
