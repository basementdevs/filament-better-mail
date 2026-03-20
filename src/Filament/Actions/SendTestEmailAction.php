<?php

namespace Basement\BetterMails\Filament\Actions;

use Basement\BetterMails\Core\Mail\AttachmentTestMail;
use Basement\BetterMails\Core\Mail\SimpleTestMail;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
            ->steps([
                Step::make('configure')
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
                        TagsInput::make('cc')
                            ->label(__('CC'))
                            ->placeholder(__('Add email address...'))
                            ->visible(fn (Get $get) => filled($get('mailable_type'))),
                        TagsInput::make('bcc')
                            ->label(__('BCC'))
                            ->placeholder(__('Add email address...'))
                            ->visible(fn (Get $get) => filled($get('mailable_type'))),
                        TextInput::make('link')
                            ->label(__('Link URL'))
                            ->url()
                            ->required()
                            ->visible(fn (Get $get) => filled($get('mailable_type'))),
                        FileUpload::make('attachment_file')
                            ->label(__('Attachment'))
                            ->disk('local')
                            ->directory('filament-better-mails/tmp')
                            ->visibility('private')
                            ->maxSize(5120)
                            ->required(fn (Get $get) => $get('mailable_type') === 'attachment')
                            ->visible(fn (Get $get) => $get('mailable_type') === 'attachment'),
                    ]),

                Step::make('preview')
                    ->label(__('Preview'))
                    ->schema([
                        Placeholder::make('email_preview')
                            ->label(__('Email Preview'))
                            ->content(function (Get $get): HtmlString {
                                $mailable = $get('mailable_type') === 'attachment'
                                    ? new AttachmentTestMail(
                                        link: $get('link') ?? '',
                                        attachmentName: basename($get('attachment_file') ?? 'attachment'),
                                        attachmentContent: '',
                                        attachmentMime: 'application/octet-stream',
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
            'attachment' => $this->buildAttachmentMail($data),
            default => new SimpleTestMail(link: $data['link']),
        };

        $send = Mail::to($data['to']);

        if (! empty($data['cc'])) {
            $send->cc($data['cc']);
        }

        if (! empty($data['bcc'])) {
            $send->bcc($data['bcc']);
        }

        $send->send($mailable);

        if (! empty($data['attachment_file'])) {
            Storage::disk('local')->delete($data['attachment_file']);
        }

        Notification::make()
            ->title(__('Test email sent successfully'))
            ->success()
            ->send();
    }

    private function buildAttachmentMail(array $data): AttachmentTestMail
    {
        $path = $data['attachment_file'];
        $disk = Storage::disk('local');

        return new AttachmentTestMail(
            link: $data['link'],
            attachmentName: basename($path),
            attachmentContent: $disk->get($path),
            attachmentMime: $disk->mimeType($path) ?: 'application/octet-stream',
        );
    }
}
