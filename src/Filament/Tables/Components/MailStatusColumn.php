<?php

declare(strict_types=1);

namespace Basement\BetterMails\Filament\Tables\Components;

use Filament\Tables\Columns\Column;

final class MailStatusColumn extends Column
{
    protected string $view = 'basement-better-mails::tables.columns.mail-status';

    public static function getDefaultName(): string
    {
        return 'mail-status';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('Status'));
    }
}
