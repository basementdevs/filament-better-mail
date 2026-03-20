<?php

namespace Basement\BetterMails\Filament\Actions;

use Basement\BetterMails\Core\Mail\AttachmentTestMail;
use Basement\BetterMails\Core\Mail\SimpleTestMail;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

final class SendTestEmailAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('Send Test Email'))
            ->icon(Heroicon::PaperAirplane)
            ->modalWidth('4xl')
            ->schema([
                Wizard::make([
                    Wizard\Step::make('configure')
                        ->label(__('Configure'))
                        ->schema([
                            Select::make('mailable_type')
                                ->label(__('Mailable Type'))
                                ->options([
                                    'simple' => __('Simple Test Email'),
                                    'attachment' => __('Test Email with Attachment'),
                                ])
                                ->required()
                                ->live(),
                            TextInput::make('to')
                                ->label(__('Recipient'))
                                ->email()
                                ->required()
                                ->visible(fn (Get $get) => filled($get('mailable_type'))),
                            TextInput::make('link')
                                ->label(__('Link URL'))
                                ->url()
                                ->required()
                                ->visible(fn (Get $get) => filled($get('mailable_type'))),
                            TextInput::make('attachment_name')
                                ->label(__('Attachment Filename'))
                                ->placeholder('e.g. invoice.txt')
                                ->visible(fn (Get $get) => $get('mailable_type') === 'attachment'),
                            Textarea::make('attachment_content')
                                ->label(__('Attachment Content'))
                                ->rows(4)
                                ->visible(fn (Get $get) => $get('mailable_type') === 'attachment'),
                        ]),

                    Wizard\Step::make('preview')
                        ->label(__('Preview'))
                        ->schema([
                            Placeholder::make('email_preview')
                                ->label(__('Email Preview'))
                                ->content(function (Get $get): HtmlString {
                                    $mailable = $get('mailable_type') === 'attachment'
                                        ? new AttachmentTestMail(
                                            link: $get('link') ?? '',
                                            attachmentName: $get('attachment_name') ?? 'attachment.txt',
                                            attachmentContent: $get('attachment_content') ?? '',
                                        )
                                        : new SimpleTestMail(link: $get('link') ?? '');

                                    $html = htmlspecialchars($mailable->render(), ENT_QUOTES, 'UTF-8');

                                    return new HtmlString(
                                        '<iframe srcdoc="'.$html.'" '
                                        .'style="width:100%;height:500px;border:1px solid #e5e7eb;border-radius:0.5rem;" '
                                        .'sandbox="allow-same-origin"></iframe>'
                                    );
                                }),
                        ]),
                ])
                    ->submitAction(
                        StaticAction::make('submit')
                            ->label(__('Send Test Email'))
                            ->submit('send')
                            ->button()
                    ),
            ])
            ->action($this->sendTestEmail(...));
    }

    public static function getDefaultName(): ?string
    {
        return 'send-test-email';
    }

    public function getLabel(): Htmlable|string|null
    {
        return __('Send Test Email');
    }

    private function sendTestEmail(array $data): void
    {
        $mailable = match ($data['mailable_type']) {
            'attachment' => new AttachmentTestMail(
                link: $data['link'],
                attachmentName: $data['attachment_name'] ?? 'attachment.txt',
                attachmentContent: $data['attachment_content'] ?? '',
            ),
            default => new SimpleTestMail(link: $data['link']),
        };

        Mail::to($data['to'])->send($mailable);

        Notification::make()
            ->title(__('Test email sent successfully'))
            ->success()
            ->send();
    }
}
