<?php

namespace Basement\BetterMails;

use Basement\BetterMails\Core\Listeners\AfterSendingMailListener;
use Basement\BetterMails\Core\Listeners\BeforeSendingMailListener;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentBetterMailsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-better-mails')
            ->hasConfigFile()
            ->hasViews()
            ->discoversMigrations();
    }

    public function boot(): void
    {
        Event::listen(MessageSending::class, BeforeSendingMailListener::class);
        Event::listen(MessageSent::class, AfterSendingMailListener::class);
    }
}
