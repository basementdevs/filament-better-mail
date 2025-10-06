<?php

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Basement\BetterMails\Tests\Fixtures\Resend\ResendWebhookDataProvider;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

it('should be able to update the emails status to delivered via webhook', function (): void {
    withoutExceptionHandling();
    $uuid = '6e289259-7918-483a-97d1-78f05dadca13';
    $mail = BetterEmail::factory()
        ->has(BetterEmailEvent::factory(), 'events')
        ->withDriver(SupportedMailProvidersEnum::Resend)
        ->create([
            'uuid' => $uuid,
        ]);

    $response = postJson(
        route('filament-better-mails.webhook.store', [
            'provider' => SupportedMailProvidersEnum::Resend
        ]),
        ResendWebhookDataProvider::mailSent(uuid: $uuid)
    );

    $response->assertOk();

    $mail->refresh();

    expect($mail->events->first()->type->value)
        ->toBe(MailEventTypeEnum::Delivered->value);

    assertDatabaseHas(BetterEmail::class, [
        'uuid' => $mail->uuid,
        'delivered_at' => now(),
    ]);
});
