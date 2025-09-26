<?php

declare(strict_types=1);

namespace Basement\BetterMails\Filament\Tables\Components;

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Js;

final class BookingProgressComponent extends TextColumn
{
    public static function getDefaultName(): string
    {
        return 'booking-progress-component';
    }

    public function toEmbeddedHtml(): string
    {

        $state = $this->getState();


        if ($state instanceof Collection) {
            $state = $state->all();
        }

        $attributes = $this->getExtraAttributeBag()
            ->class([
                'fi-ta-text',
                'fi-inline' => $this->isInline(),
            ]);

        $attributes = $attributes
            ->merge([
                'x-tooltip' => filled($tooltip = $this->getEmptyTooltip())
                    ? '{
                            content: '.Js::from($tooltip).',
                            theme: $store.theme,
                        }'
                    : null,
            ], escape: false);


        $icons = $this->buildItems($state);


        ob_start(); ?>

        <div <?= $attributes->toHtml() ?>>
            <div class="flex items-center gap-1">
                <?= $icons ?>
            </div>
        </div>

        <?php return ob_get_clean();
    }

    private function buildItems(?array $events): string
    {
        $events = collect($events);

        $cases = collect(MailEventTypeEnum::cases());

        return $cases
            ->values()
            ->map(function (MailEventTypeEnum $case, int $index) use ($events, $cases): string {
                $event = $events->firstWhere('type', $case);
                $isLast = $index === ($cases->count() - 1);

                return $event
                    ? $this->buildTimelineEventComponent($case, $isLast)
                    : $this->buildDefaultTimelineEventComponent($case, $isLast);
            })
            ->implode('');
    }

    private function buildTimelineEventComponent(MailEventTypeEnum $case, bool $isLast): string
    {
        $divider = $isLast ? '' : '<div class="ml-1 w-2.5 h-px bg-white"></div>';

        return Blade::render(<<<BLADE
            <div class="flex items-center" title="{$case->getLabel()}">
                <div class="w-6 h-6 rounded-full flex items-center justify-center cursor-help {$case->getCssClasses()}">
                    <x-filament::icon icon="heroicon-{$case->getIcon()->value}" class="w-3.5 h-3.5" />
                </div>
                {$divider}
            </div>
        BLADE
        );
    }

    private function buildDefaultTimelineEventComponent(MailEventTypeEnum $case, bool $isLast): string
    {
        $divider = $isLast ? '' : '<div class="ml-1 w-2.5 h-px bg-gray-100 dark:bg-gray-800"></div>';

        return Blade::render(<<<BLADE
            <div class="flex items-center" title="{$case->getLabel()}">
                <div class="w-6 h-6 rounded-full flex items-center justify-center cursor-help bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                    <x-filament::icon icon="heroicon-{$case->getIcon()->value}" class="w-3.5 h-3.5" />
                </div>
                {$divider}
            </div>
        BLADE
        );
    }
}
