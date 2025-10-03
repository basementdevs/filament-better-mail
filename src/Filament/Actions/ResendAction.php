<?php

namespace Basement\BetterMails\Filament\Actions;

use Basement\BetterMails\Core\DTOs\ResendMailDTO;
use Basement\BetterMails\Core\Jobs\SendEmailJob;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Filament\Schemas\BetterEmailForm;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class ResendAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->requiresConfirmation()
            ->modalDescription(__('Are you sure you want to resend this mail?'))
            ->hiddenLabel()
            ->icon(Heroicon::ArrowTurnUpRight)
            ->tooltip(__('Resend'))
            ->schema(BetterEmailForm::getResendForm())
            ->fillForm(fn (BetterEmail $record): array => [
                'to' => $record->to ?: [],
                'cc' => $record->cc ?: [],
                'bcc' => $record->bcc ?: [],
            ])
            ->action($this->sendMail(...));
    }

    public static function getDefaultName(): ?string
    {
        return 'resend-mail-action';
    }

    public function getLabel(): Htmlable|string|null
    {
        return __('Resend');
    }

    private function sendMail(BetterEmail $record, array $data): void
    {
        SendEmailJob::dispatch(ResendMailDTO::make([
            'mail' => $record,
            'to' => $data['to'] ?? [],
            'cc' => $data['cc'] ?? [],
            'bcc' => $data['bcc'] ?? [],
        ]));

        Notification::make()
            ->title(__('Mail will be resent in the background'))
            ->success()
            ->send();
    }
}
