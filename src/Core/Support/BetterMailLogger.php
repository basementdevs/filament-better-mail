<?php

namespace Basement\BetterMails\Core\Support;

use Illuminate\Support\Facades\Log;

final class BetterMailLogger
{
    private const PREFIX = 'BetterMails';

    public static function debug(string $message, array $context = []): void
    {
        if (! config('filament-better-mails.webhooks.logging.enabled', true)) {
            return;
        }

        self::log('debug', $message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        if (! config('filament-better-mails.webhooks.logging.enabled', true)) {
            return;
        }

        self::log('info', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('warning', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('error', $message, $context);
    }

    private static function log(string $level, string $message, array $context): void
    {
        $channel = config('filament-better-mails.webhooks.logging.channel');
        $prefixed = self::PREFIX.': '.$message;

        if ($channel) {
            Log::channel($channel)->{$level}($prefixed, $context);
        } else {
            Log::{$level}($prefixed, $context);
        }
    }
}
