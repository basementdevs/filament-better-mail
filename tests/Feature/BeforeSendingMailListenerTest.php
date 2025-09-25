<?php

use Basement\BetterMails\Core\Enums\SupportedMailProviders;
use Basement\BetterMails\Core\Listeners\BeforeSendingMailListener;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Tests\Fixtures\Mail\FakeMail;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('should listen to MessageSending event', function (): void {
    Event::fake();
    Event::assertListening(MessageSending::class, BeforeSendingMailListener::class);
});

it('should store an mail before sending', function () {
    Event::fake([
        MessageSent::class,
    ]);

    Mail::to('richard@3points.com')->send(new FakeMail);

    assertDatabaseCount(BetterEmail::class, 1);
    assertDatabaseHas(BetterEmail::class, [
        'mailer' => 'log',
        'subject' => 'Fake Mail',
        'from' => '"hello@example.com"',
        'to' => '"richard@3points.com"',
        'reply_to' => '"hello@example.com"',
        'cc' => '"fake@example.com"',
        'bcc' => '"fake2@example.com"',
        'mail_class' => FakeMail::class,
        'transport' => SupportedMailProviders::Resend->value,
    ]);
});
