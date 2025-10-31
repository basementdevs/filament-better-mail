# This is my package filament-better-mails

[![Latest Version on Packagist](https://img.shields.io/packagist/v/basementdevs/filament-better-mails.svg?style=flat-square)](https://packagist.org/packages/basementdevs/filament-better-mails)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/basementdevs/filament-better-mails/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/basementdevs/filament-better-mails/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/basementdevs/filament-better-mails/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/basementdevs/filament-better-mails/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/basementdevs/filament-better-mails.svg?style=flat-square)](https://packagist.org/packages/basementdevs/filament-better-mails)

A Laravel package that provides a complete implementation to track mails, starting to send the mail and receiving back mail's events by webhooks, currently the supported mailer is  ```Resend```,  and a Filament v4 resource to view sent mails in your admin panel. It ships with:

## Overview of the stack
- Language: PHP 8.3
- Framework: Laravel (Illuminate 12.x APIs)
- Admin: Filament v4
- Package manager: Composer
- Testing: Pest + Orchestra Testbench

## Requirements
- PHP ^8.3
- Laravel 12.x compatible application
- Filament ^4.0 installed in the host app
- Composer

## Installation

You can install the package via composer:

```bash
composer require basementdevs/filament-better-mails
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-better-mails-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-better-mails-config"
```

This is the contents of the published config file:

```php
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
            'key' => 'X-Better-Mails-Event-ID'
        ],

        'logging' => [
            'attachments' => [
                'enabled' => env('MAILS_LOGGING_ATTACHMENTS_ENABLED', true),
                'disk' => env('FILESYSTEM_DISK', 'local'),
                'root' => 'mails/attachments',
            ],
        ]
    ],
    'webhooks' => [
        'provider' => env('MAILS_WEBHOOK_PROVIDER', 'resend'),

        'drivers' => [
            'resend' => [
                'driver' => ResendDriver::class,
                'key_secret' => env('RESEND_WEBHOOK_SECRET', ''),
            ],
        ]
    ]
];
```

### Environment variables
Resend credentials and defaults are required by your application. The following are commonly used; verify in your project:

```env
MAIL_MAILER=resend
RESEND_API_KEY=
RESEND_WEBHOOK_SECRET=
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-better-mails-views"
```

## Usage

To use the Filament Resource you must add the ```FilamentBetterEmailPlugin``` at your panel provider. 
```php
use Basement\BetterMails\Filament\FilamentBetterEmailPlugin;

        ->plugins([
               FilamentBetterEmailPlugin::make(),
            ])
```
The resource track all mails sent based on  their status, also has a widget and actions to resend this mails again.
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Daniel Reis](https://github.com/basementdevs)
- [RichardGL11](https://github.com/RichardGL11)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
