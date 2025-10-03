<?php

namespace Basement\BetterMails\Filament\Schemas;

use Filament\Forms\Components\TagsInput;

class BetterEmailForm
{
    public static function getResendForm(): array
    {
        return [
            TagsInput::make('to')
                ->placeholder(__('Recipient(s)'))
                ->label(__('To'))
                ->required()
                ->nestedRecursiveRules(['email:rfc,dns']),
            TagsInput::make('cc')
                ->placeholder(__('Recipient(s)'))
                ->label(__('CC'))
                ->nestedRecursiveRules(['nullable', 'email:rfc,dns']),
            TagsInput::make('bcc')
                ->placeholder(__('Recipient(s)'))
                ->label(__('BCC'))
                ->nestedRecursiveRules(['nullable', 'email:rfc,dns']),
        ];
    }

    public static function getBulkResendForm($records): array
    {
        $extractEmails = (fn ($records, $field) => collect($records)
            ->map(fn ($record) => array_values($record->{$field} ?? []))
            ->flatten()
            ->unique()
            ->toArray());

        $toEmails = $extractEmails($records, 'to');
        $ccEmails = $extractEmails($records, 'cc');
        $bccEmails = $extractEmails($records, 'bcc');

        return [
            TagsInput::make('to')
                ->placeholder(__('Recipient(s)'))
                ->label(__('Recipient(s)'))
                ->default($toEmails)
                ->required()
                ->nestedRecursiveRules(['email:rfc,dns']),

            TagsInput::make('cc')
                ->placeholder(__('CC'))
                ->label(__('CC'))
                ->default($ccEmails)
                ->nestedRecursiveRules(['nullable', 'email:rfc,dns']),

            TagsInput::make('bcc')
                ->placeholder(__('BCC'))
                ->label(__('BCC'))
                ->default($bccEmails)
                ->nestedRecursiveRules(['nullable', 'email:rfc,dns']),
        ];
    }
}
