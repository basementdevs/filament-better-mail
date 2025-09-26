<?php

namespace Basement\BetterMails\Filament;

use Basement\BetterMails\Core\Http\Controllers\BetterEmailPreviewController;
use Basement\BetterMails\Filament\Widgets\BetterEmailStatsWidget;
use Filament\Contracts\Plugin;
use Filament\Panel;
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
        $panel->resources([
            BetterEmailResource::class,
        ]);
        $panel->widgets([
            BetterEmailStatsWidget::class,
        ])->routes(fn () => $this->getRoutes());
    }

    public function boot(Panel $panel): void {}

    private function getRoutes(): void
    {
        Route::get('mails/{mail}/preview', BetterEmailPreviewController::class)->name('mails.preview');
    }
}
