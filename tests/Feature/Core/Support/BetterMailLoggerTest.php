<?php

use Basement\BetterMails\Core\Support\BetterMailLogger;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

it('should use default channel when no channel is configured', function () {
    config()->set('filament-better-mails.webhooks.logging.channel', null);
    config()->set('filament-better-mails.webhooks.logging.enabled', true);

    Log::shouldReceive('warning')
        ->once()
        ->with('BetterMails: Test warning.', ['key' => 'value']);

    BetterMailLogger::warning('Test warning.', ['key' => 'value']);
});

it('should use specified channel when configured', function () {
    config()->set('filament-better-mails.webhooks.logging.channel', 'resend');
    config()->set('filament-better-mails.webhooks.logging.enabled', true);

    $logger = Mockery::mock(LoggerInterface::class);
    $logger->shouldReceive('warning')
        ->once()
        ->with('BetterMails: Test warning.', ['key' => 'value']);

    Log::shouldReceive('channel')
        ->with('resend')
        ->once()
        ->andReturn($logger);

    BetterMailLogger::warning('Test warning.', ['key' => 'value']);
});

it('should suppress info logs when logging is disabled', function () {
    config()->set('filament-better-mails.webhooks.logging.channel', null);
    config()->set('filament-better-mails.webhooks.logging.enabled', false);

    Log::shouldReceive('info')->never();

    BetterMailLogger::info('This should not be logged.');
});

it('should not suppress warning logs when logging is disabled', function () {
    config()->set('filament-better-mails.webhooks.logging.channel', null);
    config()->set('filament-better-mails.webhooks.logging.enabled', false);

    Log::shouldReceive('warning')
        ->once()
        ->with('BetterMails: This should be logged.', []);

    BetterMailLogger::warning('This should be logged.');
});

it('should suppress debug logs when logging is disabled', function () {
    config()->set('filament-better-mails.webhooks.logging.channel', null);
    config()->set('filament-better-mails.webhooks.logging.enabled', false);

    Log::shouldReceive('debug')->never();

    BetterMailLogger::debug('This should not be logged.');
});

it('should not suppress error logs when logging is disabled', function () {
    config()->set('filament-better-mails.webhooks.logging.channel', null);
    config()->set('filament-better-mails.webhooks.logging.enabled', false);

    Log::shouldReceive('error')
        ->once()
        ->with('BetterMails: This should be logged.', []);

    BetterMailLogger::error('This should be logged.');
});
