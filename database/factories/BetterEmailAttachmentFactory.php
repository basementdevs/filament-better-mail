<?php

namespace Basement\BetterMails\Database\Factories;

use Basement\BetterMails\Core\Models\BetterEmailAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class BetterEmailAttachmentFactory extends Factory
{
    protected $model = BetterEmailAttachment::class;

    public function definition(): array
    {
        return [
            'disk' => 'local',
            'uuid' => $this->faker->uuid(),
            'filename' => $this->faker->word().'.pdf',
            'mime' => 'application/pdf',
            'inline' => false,
            'size' => $this->faker->numberBetween(1024, 1048576),
        ];
    }
}
