<?php

use Basement\BetterMails\Core\Listeners\BeforeSendingMailListener;
use Basement\BetterMails\Core\Models\BetterEmailAttachment;
use Basement\BetterMails\Tests\Fixtures\Mail\FakeMail;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\Email;

function sendMailWithAttachment(string $filename = 'report.docx', string $content = 'file contents'): BetterEmailAttachment
{
    $email = (new Email)
        ->subject('Test Subject')
        ->from('from@example.com')
        ->to('richard@3points.com')
        ->html('<h1>Test HTML</h1>')
        ->attach($content, $filename, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

    (new BeforeSendingMailListener)->handle(new MessageSending($email, [
        'mailer' => 'log',
        '__laravel_mailable' => FakeMail::class,
    ]));

    return BetterEmailAttachment::query()->latest('id')->firstOrFail();
}

it('reads an attachment back from the path it was written to', function () {
    Storage::fake('local');

    $attachment = sendMailWithAttachment();

    Storage::disk('local')->assertExists($attachment->storage_path);
    expect($attachment->file_data)->toBe('file contents');
});

it('prefixes the storage path with the configured attachment root', function () {
    Storage::fake('local');

    $attachment = sendMailWithAttachment();

    expect($attachment->storage_path)
        ->toBe('mails/attachments/'.$attachment->getKey().'/report.docx');
});

it('honours a custom attachment root on both write and read', function () {
    Storage::fake('local');
    config()->set('filament-better-mails.mails.logging.attachments.root', 'custom/root/');

    $attachment = sendMailWithAttachment();

    expect($attachment->storage_path)->toBe('custom/root/'.$attachment->getKey().'/report.docx');
    Storage::disk('local')->assertExists($attachment->storage_path);
});

it('falls back to the default root when the config key is absent', function () {
    Storage::fake('local');

    $attachment = sendMailWithAttachment();

    config()->set('filament-better-mails.mails.logging.attachments', null);

    expect($attachment->fresh()->storage_path)
        ->toBe('mails/attachments/'.$attachment->getKey().'/report.docx');
});

it('can stream the attachment as a download', function () {
    Storage::fake('local');

    $attachment = sendMailWithAttachment();

    $response = $attachment->downloadFileFromStorage();

    expect($response->getStatusCode())->toBe(200);
});
