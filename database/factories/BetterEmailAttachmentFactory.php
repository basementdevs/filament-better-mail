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
            'type' => '...',
            'ip' => '',
            'hostname' => '',
            'payload' => '',
        ];
    }
}
