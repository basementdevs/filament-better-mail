<?php

namespace Basement\BetterMails\Core\Enums;

use BackedEnum;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum MailEventTypeEnum: string implements HasColor, HasIcon, HasLabel
{
    // Initial internal event when the email is created
    case Sent = 'sent';

    // Events that can be triggered by the webhooks
    case Accepted = 'accepted';
    case Clicked = 'clicked';
    case Complained = 'complained';
    case Delivered = 'delivered';
    case SoftBounced = 'soft_bounced';
    case HardBounced = 'hard_bounced';
    case Opened = 'opened';
    case Unsubscribed = 'unsubscribed';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Sent => Color::Gray,
            self::Delivered => Color::Blue,
            self::Accepted => Color::Green,
            self::Opened => Color::Green,
            self::Clicked => Color::Teal,
            self::Complained => Color::Indigo,
            self::SoftBounced => Color::Red,
            self::HardBounced => Color::Red,
            self::Unsubscribed => Color::Gray,
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Sent => 'Sent',
            self::Accepted => 'Accepted',
            self::Clicked => 'Clicked',
            self::Complained => 'Complained',
            self::Delivered => 'Delivered',
            self::SoftBounced => 'SoftBounced',
            self::HardBounced => 'HardBounced',
            self::Opened => 'Opened',
            self::Unsubscribed => 'Unsubscribed',
        };
    }

    public function getIcon(): string|BackedEnum|null
    {
        return match ($this) {
            self::Sent => Heroicon::OutlinedPaperAirplane,
            self::Accepted => Heroicon::OutlinedCheck,
            self::Clicked => Heroicon::OutlinedEnvelopeOpen,
            self::Complained => Heroicon::OutlinedCursorArrowRays,
            self::SoftBounced => Heroicon::OutlinedArrowPathRoundedSquare,
            self::Delivered => Heroicon::OutlinedInboxArrowDown,
            self::HardBounced => Heroicon::OutlinedArrowPathRoundedSquare,
            self::Opened => Heroicon::OutlinedInboxArrowDown,
            self::Unsubscribed => Heroicon::OutlinedXMark,
        };
    }

    public function getCssClasses(): string
    {
        return match ($this) {
            self::Sent => 'bg-sky-100 text-sky-800 dark:bg-sky-800 dark:text-sky-100',
            self::Accepted => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
            self::Clicked => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
            self::Complained => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
            self::SoftBounced => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
            self::Delivered => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
            self::HardBounced => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
            self::Opened => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100',
            self::Unsubscribed => 'bg-gray-500 text-white',
        };
    }
}
