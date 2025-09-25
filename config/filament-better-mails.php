<?php

// config for Basement/BetterMails
use Basement\BetterMails\Core\Models\BetterEmail;

return [
    'mails' => [
        'models' => [
            'mail' => BetterEmail::class
        ],
    ]
];
