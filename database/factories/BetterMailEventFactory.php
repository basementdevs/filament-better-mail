<?php

namespace Basement\BetterMails\Database\Factories;

use Basement\BetterMails\Core\Models\BetterEmailEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class BetterMailEventFactory extends Factory
{
    protected $model = BetterEmailEvent::class;

    public function definition(): array
    {
        return [
            'type' => 'delivered',
            'payload' => [],
        ];
    }

    public function bounce(): Factory
    {
        return $this->state(fn () => [
            'type' => 'hard_bounced',
        ]);
    }
}
