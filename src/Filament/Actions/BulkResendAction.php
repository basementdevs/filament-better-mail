<?php

namespace Basement\BetterMails\Filament\Actions;

use Basement\BetterMails\Core\DTOs\ResendMailDTO;
use Basement\BetterMails\Core\Jobs\SendEmailJob;
use Basement\BetterMails\Filament\Schemas\BetterEmailForm;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class BulkResendAction extends BUlkAction
{
    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->label(__('Resend'))
            ->icon('heroicon-o-arrow-uturn-right')
            ->requiresConfirmation()
            ->modalDescription(__('Are you sure you want to resend the selected mails?'))
            ->schema(fn ($records): array => BetterEmailForm::getBulkResendForm($records))
            ->deselectRecordsAfterCompletion()
            ->action($this->sendMails(...));
    }

    public static function getDefaultName(): ?string
    {
        return 'resend-mail-action';
    }

    private function sendMails(Collection $records, array $data): void
    {
        foreach ($records as $record) {
            SendEmailJob::dispatch(ResendMailDTO::make([
                'mail' => $record,
                'to' => $data['to'] ?? [],
                'cc' => $data['cc'] ?? [],
                'bcc' => $data['bcc'] ?? [],
            ]));
        }

        Notification::make()
            ->title(__('Mail will be resent in the background'))
            ->success()
            ->send();
    }
}
