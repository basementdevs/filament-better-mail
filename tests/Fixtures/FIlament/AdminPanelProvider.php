<?php

namespace Basement\BetterMails\Tests\Fixtures\FIlament;

use Basement\BetterMails\Filament\BetterEmailResource;
use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->resources([
                BetterEmailResource::class,
            ]);
    }
}
