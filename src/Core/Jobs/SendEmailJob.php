<?php

namespace Basement\BetterMails\Core\Jobs;

use Basement\BetterMails\Resend\Email\Actions\ResendMailAction;
use Basement\BetterMails\Resend\Email\DTOs\ResendDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, InteractsWithSockets, Queueable, SerializesModels;

    public function __construct(private readonly ResendDTO $dto) {}

    public function handle(ResendMailAction $action): void
    {
        $action->send($this->dto);
    }
}
