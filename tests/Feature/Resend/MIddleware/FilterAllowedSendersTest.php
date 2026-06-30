<?php

use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Basement\BetterMails\Resend\Email\Middleware\VerifyWebhookSignatureAdapter;

use function Pest\Laravel\postJson;

/**
 * Posts a Resend webhook with the given sender and no trackable headers.
 * Without headers the request short-circuits at VerifyHeaderWebhookSignature
 * with a distinctive 200 message, so we can tell "passed the sender filter"
 * apart from "filtered out by the sender filter".
 */
function postResendWebhook(string $from)
{
    return postJson(
        route('filament-better-mails.webhook.store', ['provider' => SupportedMailProvidersEnum::Resend]),
        [
            'data' => [
                'created_at' => '2025-10-05 23:59:51.98696+00',
                'email_id' => 'b5482019-eef5-4afd-89ae-b93acb1dda7f',
                'from' => $from,
                'subject' => 'Test Mail',
                'to' => ['delivered@resend.dev'],
            ],
            'type' => 'email.delivered',
        ]
    );
}

beforeEach(function (): void {
    $this->withoutMiddleware(VerifyWebhookSignatureAdapter::class);
});

it('processes every sender when the allow list is empty', function (): void {
    config()->set('filament-better-mails.webhooks.allowed_senders', []);

    postResendWebhook('"Flamma" <noreply@flammabeneficios.com>')
        ->assertOk()
        ->assertJson(['message' => 'Webhook received, but no trackable headers found.']);
});

it('lets the request through when the sender domain matches', function (): void {
    config()->set('filament-better-mails.webhooks.allowed_senders', ['flammabeneficios.com']);

    postResendWebhook('"Flamma" <noreply@flammabeneficios.com>')
        ->assertOk()
        ->assertJson(['message' => 'Webhook received, but no trackable headers found.']);
});

it('lets the request through when the full sender email matches', function (): void {
    config()->set('filament-better-mails.webhooks.allowed_senders', ['noreply@flammabeneficios.com']);

    postResendWebhook('"Flamma" <noreply@flammabeneficios.com>')
        ->assertOk()
        ->assertJson(['message' => 'Webhook received, but no trackable headers found.']);
});

it('ignores the event when the sender is not in the allow list', function (): void {
    config()->set('filament-better-mails.webhooks.allowed_senders', ['flammabeneficios.com']);

    postResendWebhook('"Flare" <noreply@flaredigital.com>')
        ->assertOk()
        ->assertJson(['message' => 'Webhook ignored: sender not allowed.']);
});

it('matches the sender case-insensitively', function (): void {
    config()->set('filament-better-mails.webhooks.allowed_senders', ['Flammabeneficios.COM']);

    postResendWebhook('"Flamma" <NoReply@FlammaBeneficios.com>')
        ->assertOk()
        ->assertJson(['message' => 'Webhook received, but no trackable headers found.']);
});
