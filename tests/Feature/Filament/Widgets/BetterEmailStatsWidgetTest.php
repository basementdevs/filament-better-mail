<?php

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Basement\BetterMails\Filament\Widgets\BetterEmailStatsWidget;

function statDescription(BetterEmailStatsWidget $widget, string $label): string
{
    $method = new ReflectionMethod($widget, 'getStats');
    $method->setAccessible(true);

    foreach ($method->invoke($widget) as $stat) {
        if ($stat->getLabel() === __($label)) {
            return (string) $stat->getDescription();
        }
    }

    return '';
}

it('counts emails and not events in the totals', function (): void {
    $opened = BetterEmail::factory()->create();

    foreach (range(1, 3) as $index) {
        BetterEmailEvent::factory()->for($opened, 'mail')->create([
            'type' => MailEventTypeEnum::Opened,
            'occurred_at' => now()->addMinutes($index),
        ]);
    }

    $delivered = BetterEmail::factory()->create();
    BetterEmailEvent::factory()->for($delivered, 'mail')->create([
        'type' => MailEventTypeEnum::Delivered,
        'occurred_at' => now(),
    ]);

    $widget = new BetterEmailStatsWidget;

    expect(statDescription($widget, 'Opened'))->toBe(__(':count of :total emails', ['count' => 1, 'total' => 2]))
        ->and(statDescription($widget, 'Delivered'))->toBe(__(':count of :total emails', ['count' => 1, 'total' => 2]));
});
