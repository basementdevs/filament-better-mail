<?php

namespace Basement\BetterMails\Filament\Pages;

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Filament\BetterEmailResource;
use Basement\BetterMails\Filament\Tables\Components\BookingProgressComponent;
use Basement\BetterMails\Filament\Widgets\BetterEmailStatsWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListBetterEmails extends ListRecords
{
    protected static string $resource = BetterEmailResource::class;

    public function getTitle(): string
    {
        return __('Emails');
    }

    public function getTabs(): array
    {
        return [
            Tab::make()
                ->label('All')
                ->badge(fn ($query) => BetterEmail::query()->count()),
            ...collect(MailEventTypeEnum::cases())->map(fn (MailEventTypeEnum $event) => Tab::make()
                ->icon($event->getIcon())
                ->label($event->getLabel())
                ->badgeColor($event->getColor())
                ->modifyQueryUsing(fn ($query) => $query->whereHas('events', fn ($q) => $q->where('type', $event)))
                ->badge(fn () => BetterEmail::whereHas('events', fn ($q) => $q->where('type', $event))->count())
            )->toArray(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordAction('view')
            ->recordUrl(null)
            ->defaultSort('created_at', 'desc')
            ->paginated([50, 100, 'all'])
            ->columns([
                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->limit(35)
                    ->sortable()
                    ->searchable(['subject', 'html', 'text']),
                IconColumn::make('attachments')
                    ->label('')
                    ->alignLeft()
                    ->searchable(false)
                    ->state(fn (BetterEmail $record): bool => $record->attachments->count())
                    ->icon(Heroicon::PaperClip),
                TextColumn::make('to')
                    ->label(__('Recipient(s)'))
                    ->limit(50)
                    ->getStateUsing(fn (BetterEmail $record): string => self::formatMailState(emails: $record->to, mailOnly: true))
                    ->sortable()
                    ->searchable(),
                BookingProgressComponent::make()
                    ->label('Progress')
                    ->state(fn ($record) => $record->events),
                TextColumn::make('opens')
                    ->label(__('Opens'))
                    ->tooltip(fn (BetterEmail $record): array|string|null => __('Last opened at :date', ['date' => $record->last_opened_at?->format('d-m-Y H:i')]))
                    ->sortable(),
                TextColumn::make('clicks')
                    ->label(__('Clicks'))
                    ->tooltip(fn (BetterEmail $record): array|string|null => __('Last clicked at :date', ['date' => $record->last_clicked_at?->format('d-m-Y H:i')]))
                    ->sortable(),
                TextColumn::make('sent_at')
                    ->label(__('Sent At'))
                    ->dateTime('d-m-Y H:i')
                    ->since()
                    ->tooltip(fn (BetterEmail $record) => $record->sent_at?->format('d-m-Y H:i'))
                    ->sortable()
                    ->searchable(),
            ])
            ->modifyQueryUsing(
                fn (Builder $query) => $query->with('attachments')
            )
            ->recordActions([
                ViewAction::make()
                    ->modal()
                    ->slideOver()
                    ->label(__('View'))
                    ->hiddenLabel()
                    ->tooltip(__('View')),
                //                ResendAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //                    BulkResendAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BetterEmailStatsWidget::class,
        ];
    }

    private static function formatMailState(array $emails, bool $mailOnly = false): string
    {
        return collect($emails)
            ->mapWithKeys(fn ($value, $key) => [$key => $value ?? $key])
            ->map(fn ($value, $key): string|int => $mailOnly ? $key : ($value == null ? $key : ($value !== $key ? sprintf('%s <%s>', $value, $key) : $key)))
            ->implode(', ');
    }
}
