<?php

use Basement\BetterMails\Core\Listeners\BeforeSendingMailListener;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;

it('should listen to MessageSending event', function (): void {
    Event::fake();
    Event::assertListening(MessageSending::class, BeforeSendingMailListener::class);
});

it('should do something', function (): void {

})->todo();


