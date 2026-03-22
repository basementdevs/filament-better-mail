<?php

namespace Basement\BetterMails\Filament;

use Basement\BetterMails\Filament\Pages\ListBetterEmails;
use Basement\BetterMails\Filament\Pages\ViewBetterEmail;
use Basement\BetterMails\Filament\Schemas\BetterEmailInfolist;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class BetterEmailResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'subject';

    protected static bool $isScopedToTenant = false;

    protected static bool $shouldRegisterNavigation = true;

    public static function getModel(): string
    {
        return config('filament-better-mails.mails.models.mail');
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return config('filament-better-mails.resource.slug', 'mails');
    }

    public static function getNavigationGroup(): ?string
    {
        return __(config('filament-better-mails.resource.navigation_group', 'Emails'));
    }

    public static function getNavigationLabel(): string
    {
        return __(config('filament-better-mails.resource.navigation_label', 'Emails'));
    }

    public static function getLabel(): ?string
    {
        return __(config('filament-better-mails.resource.label', 'Email'));
    }

    public static function getNavigationIcon(): \BackedEnum|Heroicon|Htmlable|string|null
    {
        return config('filament-better-mails.resource.navigation_icon', 'heroicon-o-envelope');
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
