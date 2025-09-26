<?php

namespace Basement\BetterMails\Filament\Schemas;


use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Filament\Facades\Filament;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

class BetterEmailInfolist
{
    /**
     * @throws \Exception
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->icon('heroicon-o-envelope')
                    ->compact()
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        Tabs::make('')
                            ->schema([
                                self::buildSenderInformationTab(),
                                self::buildMailStatisticsTab(),
                                self::buildMailEventsTab(),
                            ]),

                    ]),
                Section::make('Content')
                    ->icon('heroicon-o-document')
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Tabs::make('Content')
                            ->label(__('Content'))
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'w-full max-w-full'])
                            ->tabs([
                                Tab::make('Preview')
                                    ->extraAttributes(['class' => 'w-full max-w-full'])
                                    ->schema([
                                        TextEntry::make('html')
                                            ->hiddenLabel()
                                            ->label(__('HTML Content'))
                                            ->extraAttributes(['class' => 'overflow-x-auto'])
                                            ->formatStateUsing(fn (string $state, BetterEmail $record): View => view(
                                                'basement-better-mails::mails.preview',
                                                ['html' => $state, 'mail' => $record],
                                            )),
                                    ]),
                                Tab::make('HTML')
                                    ->schema([
                                        TextEntry::make('html')
                                            ->hiddenLabel()
                                            ->extraAttributes(['class' => 'overflow-x-auto'])
                                            ->formatStateUsing(fn (string $state, BetterEmail $record): View => view(
                                                'basement-better-mails::mails.html',
                                                ['html' => $state, 'mail' => $record],
                                            ))
                                            ->copyable()
                                            ->copyMessage('Copied!')
                                            ->copyMessageDuration(1500)
                                            ->label(__('HTML Content'))
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('Text')
                                    ->schema([
                                        TextEntry::make('text')
                                            ->hiddenLabel()
                                            ->copyable()
                                            ->copyMessage('Copied!')
                                            ->copyMessageDuration(1500)
                                            ->label(__('Text Content'))
                                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString(nl2br(e($state))))
                                            ->columnSpanFull(),
                                    ]),
                            ])->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Attachments')
                    ->icon('heroicon-o-paper-clip')
                    ->compact()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('attachments')
                            ->hiddenLabel()
                            ->label(__('Attachments'))
                            ->visible(fn (BetterEmail $record): bool => $record->attachments->count() == 0)
                            ->default(__('Email has no attachments')),
                        RepeatableEntry::make('attachments')
                            ->hiddenLabel()
                            ->label(__('Attachments'))
                            ->visible(fn (BetterEmail $record): bool => $record->attachments->count() > 0)
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('filename')
                                            ->label(__('Name')),
                                        TextEntry::make('size')
                                            ->label(__('Size')),
                                        TextEntry::make('mime')
                                            ->label(__('Mime Type')),
                                        ViewEntry::make('uuid')
                                            ->label(__('Download'))
                                            ->state(fn ($record) => $record)
                                            ->view('basement-better-mails::mails.download'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function buildSenderInformationTab(): Tab
    {
        return Tab::make(__('Sender Information'))
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextEntry::make('subject')
                            ->columnSpanFull()
                            ->label(__('Subject')),
                        TextEntry::make('from')
                            ->label(__('From'))
                            ->state(fn (BetterEmail $record): string => self::formatMailState($record->from)),
                        TextEntry::make('to')
                            ->label(__('Recipient(s)'))
                            ->state(fn (BetterEmail $record): string => self::formatMailState($record->to)),
                        TextEntry::make('cc')
                            ->label(__('CC'))
                            ->default('-')
                            ->state(fn (BetterEmail $record): string => self::formatMailState($record->cc ?? [])),
                        TextEntry::make('bcc')
                            ->label(__('BCC'))
                            ->default('-')
                            ->state(fn (BetterEmail $record): string => self::formatMailState($record->bcc ?? [])),
                        TextEntry::make('reply_to')
                            ->default('-')
                            ->label(__('Reply To'))
                            ->state(fn (BetterEmail $record): string => self::formatMailState($record->reply_to ?? [])),
                    ]),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function buildMailStatisticsTab(): Tab
    {
        return Tab::make(__('Statistics'))
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextEntry::make('opens')
                            ->label(__('Opens')),
                        TextEntry::make('clicks')
                            ->label(__('Clicks')),
                        TextEntry::make('sent_at')
                            ->label(__('Sent At'))
                            ->default(__('Never'))
                            ->formatStateUsing(fn ($state): string|array|null => $state === __('Never') ? $state : Carbon::parse($state)->format('d-m-Y H:i')),
                        TextEntry::make('resent_at')
                            ->label(__('Resent At'))
                            ->default(__('Never'))
                            ->formatStateUsing(fn ($state): string|array|null => $state === __('Never') ? $state : Carbon::parse($state)->format('d-m-Y H:i')),
                        TextEntry::make('delivered_at')
                            ->label(__('Delivered At'))
                            ->default(__('Never'))
                            ->formatStateUsing(fn ($state): string|array|null => $state === __('Never') ? $state : Carbon::parse($state)->format('d-m-Y H:i')),
                        TextEntry::make('last_opened_at')
                            ->label(__('Last Opened At'))
                            ->default(__('Never'))
                            ->formatStateUsing(fn ($state): string|array|null => $state === __('Never') ? $state : Carbon::parse($state)->format('d-m-Y H:i')),
                        TextEntry::make('last_clicked_at')
                            ->label(__('Last Clicked At'))
                            ->default(__('Never'))
                            ->formatStateUsing(fn ($state): string|array|null => $state === __('Never') ? $state : Carbon::parse($state)->format('d-m-Y H:i')),
                        TextEntry::make('complained_at')
                            ->label(__('Complained At'))
                            ->default(__('Never'))
                            ->formatStateUsing(fn ($state): string|array|null => $state === __('Never') ? $state : Carbon::parse($state)->format('d-m-Y H:i')),
                        TextEntry::make('soft_bounced_at')
                            ->label(__('Soft Bounced At'))
                            ->default(__('Never'))
                            ->formatStateUsing(fn ($state): string|array|null => $state === __('Never') ? $state : Carbon::parse($state)->format('d-m-Y H:i')),
                        TextEntry::make('hard_bounced_at')
                            ->label(__('Hard Bounced At'))
                            ->default(__('Never'))
                            ->formatStateUsing(fn ($state): string|array|null => $state === __('Never') ? $state : Carbon::parse($state)->format('d-m-Y H:i')),
                    ]),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function buildMailEventsTab(): Tab
    {
        return Tab::make(__('Events'))
            ->schema([
                RepeatableEntry::make('events')
                    ->state(fn (BetterEmail $record) => $record->events->sortByDesc('occurred_at'))
                    ->hiddenLabel()
                    ->schema([
                        TextEntry::make('type')
                            ->label(__('Type'))
                            ->badge()
                            ->url(function (BetterEmailEvent $record): ?string {
                                $panel = Filament::getCurrentOrDefaultPanel();
                                $tenant = Filament::getTenant();

                                if (! $panel || ! $tenant) {
                                    return null;
                                }

                                return route('filament.'.$panel->getId().'.resources.mails.events.view', [
                                    'record' => $record,
                                    'tenant' => $tenant->getKey(),
                                ]);
                            })
                            ->color(fn (MailEventTypeEnum $state): string => match ($state) {
                                MailEventTypeEnum::Delivered => 'success',
                                MailEventTypeEnum::Clicked => 'clicked',
                                MailEventTypeEnum::Opened => 'info',
                                MailEventTypeEnum::Sent => 'danger',
                                MailEventTypeEnum::HardBounced => 'danger',
                                MailEventTypeEnum::Complained => 'warning',
                                MailEventTypeEnum::Unsubscribed => 'danger',
                                MailEventTypeEnum::Accepted => 'success',
                            })
                            ->formatStateUsing(fn (MailEventTypeEnum $state): string => ucfirst($state->value)),
                        TextEntry::make('occurred_at')
                            ->url(function (BetterEmailEvent $record): ?string {
                                $panel = Filament::getCurrentOrDefaultPanel();
                                $tenant = Filament::getTenant();

                                if (! $panel || ! $tenant) {
                                    return null;
                                }

                                return route('filament.'.$panel->getId().'.resources.mails.view', [
                                    'record' => $record,
                                    'tenant' => $tenant->getKey(),
                                ]);
                            })
                            ->since()
                            ->dateTimeTooltip('d-m-Y H:i')
                            ->label(__('Occurred At')),
                    ])
                    ->columns(2),
            ]);
    }

    private static function formatMailState(array $emails, bool $mailOnly = false): string
    {
        return collect($emails)
            ->mapWithKeys(fn ($value, $key) => [$key => $value ?? $key])
            ->map(fn ($value, $key): string|int => $mailOnly ? $key : ($value == null ? $key : ($value !== $key ? sprintf('%s <%s>', $value, $key) : $key)))
            ->implode(', ');
    }
}
