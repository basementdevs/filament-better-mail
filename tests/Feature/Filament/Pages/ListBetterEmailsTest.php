<?php

use Basement\BetterMails\Filament\Pages\ListBetterEmails;
use function Pest\Livewire\livewire;


it('should render', function (): void {
   livewire(ListBetterEmails::class)
       ->assertOk();
})->skip();
