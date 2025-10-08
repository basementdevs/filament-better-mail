<?php

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Enums\SupportedMailProvidersEnum;
use Basement\BetterMails\Core\Http\Controllers\WebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/{provider}', WebhookController::class)
    ->whereIn('provider', SupportedMailProvidersEnum::cases())
    ->withoutMiddleware(VerifyCsrfToken::class)
    ->name('filament-better-mails.webhook.store');
