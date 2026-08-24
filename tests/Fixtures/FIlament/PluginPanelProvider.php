<?php

namespace Basement\BetterMails\Tests\Fixtures\FIlament;

use Basement\BetterMails\Filament\FilamentBetterEmailPlugin;
use Basement\BetterMails\Tests\Fixtures\Http\DenyMiddleware;
use Filament\Panel;
use Filament\PanelProvider;

class PluginPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('plugin-test')
            ->path('plugin-test')
            ->authMiddleware([DenyMiddleware::class])
            ->plugin(FilamentBetterEmailPlugin::make());
    }
}
