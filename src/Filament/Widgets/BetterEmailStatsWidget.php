<?php

namespace Basement\BetterMails\Filament\Widgets;

use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BetterEmailStatsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected string $view = 'filament-widgets::stats-overview-widget';

    protected static bool $isDiscovered = false;

    public static function canView(): bool
    {
        return config('filament-better-mails.view_any', true);
    }

    protected function getStats(): array
    {
        $class = config('filament-better-mails.mails.models.event');

        $bouncedMails = $class::where(fn ($query) => $query->softBounced()->orWhere(fn ($query) => $query->hardBounced()))->count();
        $openedMails = $class::opened()->count();
        $deliveredMails = $class::delivered()->count();
        $clickedMails = $class::clicked()->count();

        $mailCount = $class::count();

        if ($mailCount === 0) {
            return [];
        }

        $generateUrl = function (string $activeTab): ?string {
            $panel = Filament::getCurrentOrDefaultPanel();
            $tenant = Filament::getTenant();

            if (! $panel || ! $tenant) {
                return null;
            }

            return route('filament.'.$panel->getId().'.resources.mails.index', [
                'activeTab' => $activeTab,
                'tenant' => $tenant,
            ]);
        };

        $widgets[] = Stat::make(__('Delivered'), number_format(($deliveredMails / $mailCount) * 100, 1).'%')
            ->label(__('Delivered'))
            ->description(__(':count of :total emails', ['count' => $deliveredMails, 'total' => $mailCount]))
            ->color('success')
            ->url($generateUrl('delivered'));

        $widgets[] = Stat::make(__('Opened'), number_format(($openedMails / $mailCount) * 100, 1).'%')
            ->label(__('Opened'))
            ->description(__(':count of :total emails', ['count' => $openedMails, 'total' => $mailCount]))
            ->color('info')
            ->url($generateUrl('opened'));

        $widgets[] = Stat::make(__('Clicked'), number_format(($clickedMails / $mailCount) * 100, 1).'%')
            ->label(__('Clicked'))
            ->description(__(':count of :total emails', ['count' => $clickedMails, 'total' => $mailCount]))
            ->color('clicked')
            ->url($generateUrl('clicked'));

        $widgets[] = Stat::make(__('Bounced'), number_format(($bouncedMails / $mailCount) * 100, 1).'%')
            ->label(__('Bounced'))
            ->description(__(':count of :total emails', ['count' => $bouncedMails, 'total' => $mailCount]))
            ->color('danger')
            ->url($generateUrl('bounced'));

        return $widgets;
    }
}
