<?php

namespace Basement\BetterMails\Filament;

use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Filament\Pages\ListBetterEmails;
use Basement\BetterMails\Filament\Pages\ViewBetterEmail;
use Basement\BetterMails\Filament\Schemas\BetterEmailInfolist;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class BetterEmailResource extends Resource
{
    protected static ?string $slug = 'mails';

    protected static ?string $recordTitleAttribute = 'subject';

    protected static bool $isScopedToTenant = false;

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $model = BetterEmail::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Emails');
    }

    public static function getNavigationLabel(): string
    {
        return __('Emails');
    }

    public static function getLabel(): ?string
    {
        return __('Email');
    }

    public static function getNavigationIcon(): \BackedEnum|Heroicon|Htmlable|string|null
    {
        return Heroicon::Envelope;
    }

    public static function infolist(Schema $schema): Schema
    {
        return BetterEmailInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBetterEmails::route('/'),
            'view' => ViewBetterEmail::route('/{record}/view'),
        ];
    }

    public function getTitle(): string
    {
        return __('Emails');
    }
}
