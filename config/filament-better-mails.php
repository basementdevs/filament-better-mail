<?php

// config for Basement/BetterMails
use Basement\BetterMails\Core\Models\BetterEmail;
use Basement\BetterMails\Core\Models\BetterEmailAttachment;
use Basement\BetterMails\Core\Models\BetterEmailEvent;

return [
    'mails' => [
        'models' => [
            'mail' => BetterEmail::class,
            'event' => BetterEmailEvent::class,
            'attachment' => BetterEmailAttachment::class,
        ],
        'database' => [
            'tables' => [
                'mails' => 'mails',
                'attachments' => 'mail_attachments',
                'events' => 'mail_events',
                'polymorph' => 'mailables',
            ],
            'pruning' => [
                'enabled' => false,
                'after' => 30, // days
            ],
        ],

        'headers' => [
            'key' => 'X-Better-Mails-Event-Id'
        ],

        'logging' => [
            'attachments' => [
                'enabled' => env('MAILS_LOGGING_ATTACHMENTS_ENABLED', true),
                'disk' => env('FILESYSTEM_DISK', 'local'),
                'root' => 'mails/attachments',
            ],
        ]
    ]
];
