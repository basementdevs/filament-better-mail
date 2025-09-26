<?php

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Enums\SupportedMailProviders;
use Basement\BetterMails\Core\Listeners\AfterSendingMailListener;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Basement\BetterMails\Tests\Fixtures\Mail\FakeMail;
use Illuminate\Mail\Events\MessageSent;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('should listen to MessageSent event', function (): void {
    Event::fake();
    Event::assertListening(MessageSent::class, AfterSendingMailListener::class);
});

it('should update sent_at when MessageSent is captured', function (): void {
    Mail::to('richard@3points.com')->send(new FakeMail);
    assertDatabaseCount(BetterEmail::class, 1);
    assertDatabaseHas(BetterEmail::class, [
        'mailer' => 'log',
        'subject' => 'Fake Mail',
        'from' => json_encode(['hello@example.com']),
        'to' => json_encode(['richard@3points.com']),
        'reply_to' => json_encode(['hello@example.com']),
        'cc' => json_encode(['fake@example.com']),
        'bcc' => json_encode(['fake2@example.com']),
        'mail_class' => FakeMail::class,
        'transport' => SupportedMailProviders::Resend->value,
        'sent_at' => now(),
    ]);
});

it('should register an event after MessageSent', function () {
    Mail::to('richard@3points.com')->send(new FakeMail);

    assertDatabaseCount(BetterEmail::class, 1);
    assertDatabaseCount(BetterEmailEvent::class, 1);

    $email = BetterEmail::query()->first();

    assertDatabaseHas(BetterEmailEvent::class, [
        'mail_id' => $email->getKey(),
        'type' => MailEventTypeEnum::Sent,
    ]);
});
