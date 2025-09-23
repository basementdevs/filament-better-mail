<?php

namespace Basement\BetterMails\Database\Factories;

use Basement\BetterMails\Core\Models\BetterEmail;
use Illuminate\Database\Eloquent\Factories\Factory;

class BetterMailFactory extends Factory
{
    protected $model = BetterEmail::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'mailer' => 'smtp',
            'mail_class' => '',
            'subject' => $this->faker->sentence(5),
            'from' => [
                $this->faker->email => $this->faker->firstName(),
            ],
            'reply_to' => null,
            'to' => [
                $this->faker->email => $this->faker->firstName(),
            ],
            'cc' => null,
            'bcc' => null,
            'sent_at' => now(),
            'delivered_at' => null,
            'last_opened_at' => null,
            'last_clicked_at' => null,
            'complained_at' => null,
            'soft_bounced_at' => null,
            'hard_bounced_at' => null,
        ];
    }

    public function hasCc(): static
    {
        return $this->state(fn () => [
            'cc' => [
                $this->faker->email => $this->faker->firstName(),
            ],
        ]);
    }

    public function hasBcc(): BetterMailFactory
    {
        return $this->state(fn () => [
            'bcc' => [
                $this->faker->email => $this->faker->firstName(),
            ],
        ]);
    }
}
