<?php

namespace Basement\BetterMails\Filament;

use Basement\BetterMails\Core\Http\Controllers\BetterEmailPreviewController;
use Basement\BetterMails\Filament\Widgets\BetterEmailStatsWidget;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Route;

final class FilamentBetterEmailPlugin implements Plugin
{
    public static function make(): self
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'filament-better-email';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([BetterEmailResource::class])
            ->widgets([BetterEmailStatsWidget::class])
            ->routes(fn () => $this->getRoutes())
            ->viteTheme('vendor/basementdevs/filament-better-mails/resources/css/theme.css')
            ->colors([
                'blue' => Color::Blue,
                'green' => Color::Green,
                'yellow' => Color::Yellow,
                'red' => Color::Red,
            ]);
    }

    public function boot(Panel $panel): void {}

    private function getRoutes(): void
    {
        Route::get('mails/{mail}/preview', BetterEmailPreviewController::class)->name('mails.preview');
    }
}
