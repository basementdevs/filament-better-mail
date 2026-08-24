<?php

namespace Basement\BetterMails\Tests\Fixtures\FIlament;

use Basement\BetterMails\Filament\FilamentBetterEmailPlugin;
use Filament\Panel;
use Filament\PanelProvider;

class PluginPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('plugin-test')
            ->path('plugin-test')
            ->plugin(FilamentBetterEmailPlugin::make());
    }
}
