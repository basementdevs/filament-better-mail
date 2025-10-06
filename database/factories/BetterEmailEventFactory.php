<?php

namespace Basement\BetterMails\Database\Factories;

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

final class BetterEmailEventFactory extends Factory
{
    protected $model = BetterEmailEvent::class;

    public function definition(): array
    {
        return [
            'mail_id' => BetterEmail::factory(),
            'type' => MailEventTypeEnum::Sent,
            'payload' => [],
        ];
    }
    public function withEvent(MailEventTypeEnum $type): Factory
    {
        return $this->state(fn () => [
            'type' => $type,
        ]);
    }
}
