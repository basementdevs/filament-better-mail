<?php

namespace Basement\BetterMails;

use Basement\BetterMails\Core\Contracts\BetterDriverContract;
use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Listeners\AfterSendingMailListener;
use Basement\BetterMails\Core\Listeners\BeforeSendingMailListener;
use Basement\BetterMails\Core\Listeners\External\ClickedMailListener;
use Basement\BetterMails\Core\Listeners\External\ComplainedMailListener;
use Basement\BetterMails\Core\Listeners\External\DeliveredMailListener;
use Basement\BetterMails\Core\Listeners\External\FailedMailListener;
use Basement\BetterMails\Core\Listeners\External\HardBouncedMailListener;
use Basement\BetterMails\Core\Listeners\External\OpenedMailListener;
use Basement\BetterMails\Core\Listeners\External\ReceivedMailListener;
use Basement\BetterMails\Resend\Email\Events\ResendEmailClickedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailComplainedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailDeliveredEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailFailedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailHardBouncedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailOpenedEvent;
use Basement\BetterMails\Resend\Email\Events\ResendEmailReceivedEvent;
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

    /**
     * @throws \Exception
     */
    public function boot(): void
    {
        $this->loadListeners();
        $this->loadResendListeners();
        $this->loadProviderConfig();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'basement-better-mails');
        $this->loadRoutesFrom(__DIR__.'/../routes/filament-better-mails-route.php');
    }

    private function loadListeners(): void
    {
        Event::listen(MessageSending::class, BeforeSendingMailListener::class);
        Event::listen(MessageSent::class, AfterSendingMailListener::class);
    }

    private function loadProviderConfig(): void
    {
        $provider = config('filament-better-mails.webhooks.provider');

        $config = config("filament-better-mails.webhooks.drivers.{$provider}");

        if (! $config) {
            throw new \Exception('Invalid provider configuration');
        }

        $this->app->bind(BetterDriverContract::class, $config['driver']);
        $this->app->bind(BetterMiddlewareContract::class, $config['middleware']);
    }

    private function loadResendListeners(): void
    {
        Event::listen(ResendEmailDeliveredEvent::class, DeliveredMailListener::class);
        Event::listen(ResendEmailOpenedEvent::class, OpenedMailListener::class);
        Event::listen(ResendEmailClickedEvent::class, ClickedMailListener::class);
        Event::listen(ResendEmailComplainedEvent::class, ComplainedMailListener::class);
        Event::listen(ResendEmailHardBouncedEvent::class, HardBouncedMailListener::class);
        Event::listen(ResendEmailReceivedEvent::class, ReceivedMailListener::class);
        Event::listen(ResendEmailFailedEvent::class, FailedMailListener::class);
        // TODO: sent, delivered_delayed
    }
}
