<?php

namespace Basement\BetterMails\Core\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Termwind\Enums\Color;

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
            self::Sent => Color::BLUE,
            self::Accepted => Color::GREEN,
            self::Clicked => Color::YELLOW,
            self::Complained => Color::RED,
            self::Delivered => Color::YELLOW,
            self::SoftBounced => Color::RED,
            self::HardBounced => Color::YELLOW,
            self::Opened => Color::YELLOW,
            self::Unsubscribed => Color::RED,
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
            self::Sent => 'bg-blue-500 text-white',
            self::Accepted => 'bg-emerald-600 text-white',
            self::Clicked => 'bg-indigo-500 text-white',
            self::Complained => 'bg-yellow-500 text-white',
            self::SoftBounced => 'bg-orange-500 text-white',
            self::Delivered => 'bg-green-500 text-white',
            self::HardBounced => 'bg-red-600 text-white',
            self::Opened => 'bg-purple-500 text-white',
            self::Unsubscribed => 'bg-gray-500 text-white',
        };
    }
}
