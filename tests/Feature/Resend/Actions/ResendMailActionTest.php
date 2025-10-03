<?php

use Basement\BetterMails\Filament\Actions\ResendAction;
use Basement\BetterMails\Filament\Pages\ListBetterEmails;
use Filament\Actions\Testing\TestAction;

use function Pest\Livewire\livewire;

it('should resend the email', function (): void {
    livewire(ListBetterEmails::class)
        ->callAction(TestAction::make(ResendAction::class))
        ->assertHasNoFormErrors();
})->skip();
