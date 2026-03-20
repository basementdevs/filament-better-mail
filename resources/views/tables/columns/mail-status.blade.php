@php
    $events = collect($getState() ?? []);
    $eventTypes = $events->pluck('type')->unique();
    $mostSignificant = $eventTypes->sortByDesc(fn ($t) => $t->getPriority())->first();
    $lifecycleStages = \Basement\BetterMails\Core\Enums\MailEventTypeEnum::LIFECYCLE_STAGES;
@endphp

<div class="flex flex-col items-start gap-1">
    @if ($mostSignificant)
        <x-filament::badge :color="$mostSignificant->getBadgeCssColor()" size="sm">
            {{ $mostSignificant->getLabel() }}
        </x-filament::badge>
    @else
        <span class="text-sm text-gray-400 dark:text-gray-500 italic">
            {{ __('No events') }}
        </span>
    @endif

    <div class="flex items-center gap-1">
        @foreach ($lifecycleStages as $stage)
            <span
                x-tooltip="{ content: '{{ $stage->getLabel() }}', theme: $store.theme }"
                @class([
                    'inline-block w-2 h-2 rounded-full cursor-help',
                    $stage->getCssClasses() => $eventTypes->contains($stage),
                    'bg-gray-200 dark:bg-gray-700' => ! $eventTypes->contains($stage),
                ])
            ></span>
        @endforeach
    </div>
</div>
