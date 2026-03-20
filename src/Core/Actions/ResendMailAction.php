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
            ->to($dto->to);

        if (! empty($dto->mail->from)) {
            $from = array_values($dto->mail->from)[0];
            $message->from($from, $from);
        }

        $cc = ! empty($dto->cc) ? $dto->cc : ($dto->mail->cc ?? []);
        if (! empty($cc)) {
            $message->cc($cc);
        }

        $bcc = ! empty($dto->bcc) ? $dto->bcc : ($dto->mail->bcc ?? []);
        if (! empty($bcc)) {
            $message->bcc($bcc);
        }

        $replyTo = ! empty($dto->replyTo) ? $dto->replyTo : ($dto->mail->reply_to ?? []);
        if (! empty($replyTo)) {
            $message->replyTo($replyTo);
        }

        return $this;
    }
}
