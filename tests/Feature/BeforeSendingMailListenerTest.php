<?php

use Basement\BetterMails\Core\Enums\SupportedMailProviders;
use Basement\BetterMails\Core\Listeners\BeforeSendingMailListener;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Tests\Fixtures\Mail\FakeMail;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mime\Email;

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

it('should add X-Better-Mails-Event-Id text header to mail', function () {
    Mail::fake();

    $email = (new Email)
        ->subject('Test Subject')
        ->from('from@example.com')
        ->to('richard@3points.com')
        ->cc('cc@example.com')
        ->bcc('bcc@example.com')
        ->replyTo('replyto@example.com')
        ->html('<h1>Test HTML</h1>')
        ->text('Test Text');

    $listener = new BeforeSendingMailListener;

    $event = new MessageSending($email, [
        'mailer' => 'resend',
        '__laravel_mailable' => FakeMail::class
    ]);

    $listener->handle($event);

    $headers = $email->getHeaders();
    expect($headers->get('X-Better-Mails-Event-Id'))->not->toBeNull();

    $headerValue = $headers->get('X-Better-Mails-Event-Id')->getBody();
    expect(Uuid::isValid($headerValue))->toBeTrue();
});
