<?php

namespace Basement\BetterMails\Core\Actions;

use Basement\BetterMails\Core\DTOs\ResendMailDTO;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

final class ResendMailAction
{
    public function send(ResendMailDTO $dto): void
    {
        Mail::send([], [], function (Message $message) use ($dto) {
            $this->setMessageContent($message, $dto)
                ->setMessageRecipients($message, $dto);
        });
    }

    private function setMessageContent(Message $message, ResendMailDTO $dto): self
    {
        $message->html($dto->mail->html ?? '')
            ->text($dto->mail->text ?? '');

        foreach ($dto->mail->attachments as $attachment) {
            $message->attachData(
                $attachment->file_data ?? $attachment->fileData ?? '',
                $attachment->file_name ?? $attachment->filename ?? '',
                ['mime' => $attachment->mime_type ?? $attachment->mime ?? '']
            );
        }

        return $this;
    }

    private function setMessageRecipients(Message $message, ResendMailDTO $dto): self
    {
        $message->subject($dto->mail->subject ?? '')
            ->from(array_values($dto->mail->from)[0], array_values($dto->mail->from)[0])
            ->to($dto->to);

        if ($dto->mail->cc || count($dto->cc) > 0) {
            $message->cc($dto->mail->cc ?? $dto->cc);
        }

        if ($dto->mail->bcc || count($dto->bcc) > 0) {
            $message->bcc($dto->mail->bcc ?? $dto->bcc);
        }

        if ($dto->mail->reply_to || $dto->replyTo) {
            $message->replyTo($dto->mail->reply_to ?? $dto->replyTo);
        }

        return $this;
    }
}
