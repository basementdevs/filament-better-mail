# Filament Better Mails

[![Latest Version on Packagist](https://img.shields.io/packagist/v/basementdevs/filament-better-mails.svg?style=flat-square)](https://packagist.org/packages/basementdevs/filament-better-mails)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/basementdevs/filament-better-mails/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/basementdevs/filament-better-mails/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/basementdevs/filament-better-mails/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/basementdevs/filament-better-mails/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/basementdevs/filament-better-mails.svg?style=flat-square)](https://packagist.org/packages/basementdevs/filament-better-mails)

A Filament v5 plugin that automatically logs every outgoing email, tracks delivery events via provider webhooks, and gives you a full-featured admin panel to browse, preview, and resend emails.

## Features

- **Zero-config email logging** -- every outgoing email is captured automatically
- **Webhook-based delivery tracking** -- delivered, opened, clicked, bounced, complained, and more
- **Email preview** -- rendered HTML, raw HTML source, and plain text views
- **Resend emails** -- individually or in bulk with custom recipients
- **Send test emails** -- simple or with attachments, directly from the admin panel
- **Attachment storage** -- stored to disk with download support
- **Dashboard stats widget** -- delivery, open, click, and bounce rates at a glance
- **Fully config-driven** -- customize table names, swap models, configure resource navigation
- **Auto-pruning** -- schedule cleanup of old email records
- **Dark mode support**

## Requirements

- PHP 8.3+
- Laravel 12.x or 13.x
- Filament 5.x

## Installation

Install the package via Composer:

```bash
composer require basementdevs/filament-better-mails
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag="filament-better-mails-migrations"
php artisan migrate
```

Publish the config file:

```bash
php artisan vendor:publish --tag="filament-better-mails-config"
```

## Setup

### 1. Register the Plugin

Add the plugin to your Filament panel provider:

```php
use Basement\BetterMails\Filament\FilamentBetterEmailPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(FilamentBetterEmailPlugin::make());
}
```

### 2. Configure Your Email Provider

Set your provider credentials in `.env`:

```env
MAIL_MAILER=resend
RESEND_API_KEY=your-api-key
RESEND_WEBHOOK_SECRET=your-webhook-secret
MAILS_WEBHOOK_PROVIDER=resend
```

### 3. Register Webhook URL

Point your email provider's webhook settings to:

```
POST https://your-app.com/webhook/resend
```

This route is automatically registered and CSRF-exempt.

## How It Works

### Email Capture Flow

The package hooks into Laravel's mail events automatically. No changes to your existing mail code are required.

```
 YOUR APP                                  BETTER MAILS
  |                                           |
  |  Mail::send(new OrderConfirmation)        |
  | ----------------------------------------> |
  |                                           |  BeforeSendingMailListener
  |                                           |  - Generate tracking UUID
  |                                           |  - Store email record (subject, body, recipients)
  |                                           |  - Store attachments to disk
  |                                           |  - Inject UUID into email headers
  |                                           |
  |                                           |  AfterSendingMailListener
  |                                           |  - Mark record as sent
  |                                           |  - Create "Sent" event
  |                                           |
```

### Webhook Tracking Flow

When your email provider processes the email, it sends delivery events back:

```
 YOUR APP                   RESEND                    RECIPIENT
  |                           |                          |
  |  -- sends email --------> |  -- delivers ----------> |
  |                           |                          |
  |                           |  <-- opens email ------- |
  |                           |  <-- clicks link ------  |
  |                           |
  |  <-- POST /webhook/resend |
  |       { event: opened }   |
  |                           |
  |  Updates mail record      |
  |  Creates event timeline   |
```

### Tracked Events

| Event | Color | Description |
|-------|-------|-------------|
| Sent | Gray | Email dispatched from your app |
| Accepted | Green | Provider accepted the email |
| Scheduled | Amber | Email scheduled for future delivery |
| Delivered | Blue | Email reached recipient's inbox |
| Opened | Green | Recipient opened the email |
| Clicked | Teal | Recipient clicked a link in the email |
| Soft Bounced | Red | Temporary delivery failure |
| Hard Bounced | Red | Permanent delivery failure |
| Complained | Indigo | Recipient marked as spam |
| Unsubscribed | Gray | Recipient unsubscribed |
| Suppressed | Orange | Email suppressed by provider |

## Configuration

<details>
<summary>Full configuration reference</summary>

```php
return [
    'mails' => [
        'models' => [
            'mail' => \Basement\BetterMails\Core\Models\BetterEmail::class,
            'event' => \Basement\BetterMails\Core\Models\BetterEmailEvent::class,
            'attachment' => \Basement\BetterMails\Core\Models\BetterEmailAttachment::class,
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
            'key' => 'X-Better-Mails-Event-ID',
        ],
        'logging' => [
            'attachments' => [
                'enabled' => env('MAILS_LOGGING_ATTACHMENTS_ENABLED', true),
                'disk' => env('FILESYSTEM_DISK', 'local'),
                'root' => 'mails/attachments',
            ],
        ],
    ],
    'webhooks' => [
        'provider' => env('MAILS_WEBHOOK_PROVIDER', 'resend'),
        'logging' => [
            'channel' => env('MAILS_WEBHOOK_LOG_CHANNEL'),
            'enabled' => env('MAILS_WEBHOOK_LOGGING_ENABLED', false),
        ],
        'drivers' => [
            'resend' => [
                'driver' => \Basement\BetterMails\Resend\ResendDriver::class,
                'key_secret' => env('RESEND_WEBHOOK_SECRET'),
            ],
        ],
    ],
    'resource' => [
        'navigation_group' => 'Emails',
        'navigation_label' => 'Emails',
        'label' => 'Email',
        'slug' => 'mails',
        'navigation_icon' => 'heroicon-o-envelope', // null to hide icon
    ],
    'view_any' => true,
];
```

</details>

### Models

Swap the default models with your own by extending the base classes:

```php
'models' => [
    'mail' => \App\Models\Email::class,
    'event' => \App\Models\EmailEvent::class,
    'attachment' => \App\Models\EmailAttachment::class,
],
```

All internal references resolve from config, so your custom models are used throughout the package.

### Database Tables

Customize table names to avoid conflicts:

```php
'database' => [
    'tables' => [
        'mails' => 'email_logs',
        'attachments' => 'email_attachments',
        'events' => 'email_events',
        'polymorph' => 'email_mailables',
    ],
],
```

### Resource Navigation

Customize how the resource appears in the Filament sidebar:

```php
'resource' => [
    'navigation_group' => 'Communications',
    'navigation_label' => 'Email Logs',
    'label' => 'Email Log',
    'slug' => 'email-logs',
    'navigation_icon' => 'heroicon-o-inbox',  // set to null to hide icon
],
```

### Pruning

Enable automatic cleanup of old records:

```php
'pruning' => [
    'enabled' => true,
    'after' => 60, // days
],
```

Then schedule the prune command in your `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune', [
    '--model' => \Basement\BetterMails\Core\Models\BetterEmail::class,
])->daily();
```

### Attachment Logging

Control whether attachments are stored to disk:

```php
'logging' => [
    'attachments' => [
        'enabled' => true,       // set to false to skip attachment storage
        'disk' => 'local',       // any filesystem disk
        'root' => 'mails/attachments',
    ],
],
```

### Webhook Logging

Enable detailed webhook logging for debugging:

```php
'webhooks' => [
    'logging' => [
        'enabled' => true,
        'channel' => 'webhook',  // custom log channel
    ],
],
```

## Filament Admin Panel

### Email List Page

The list page provides:

- **Tabs** -- filter by event type (All, Sent, Delivered, Opened, Clicked, Bounced, etc.) with badge counts
- **Search** -- across subject, HTML body, plain text, and recipients
- **Sort** -- by subject, opens, clicks, or sent date
- **Pagination** -- 50, 100, or all records

### Table Columns

| Column | Description |
|--------|-------------|
| Subject | Email subject line (searchable) |
| Attachments | Paper clip icon if email has attachments |
| Recipient(s) | To addresses |
| Status | Color-coded badges for each event in the timeline |
| Opens | Open count with tooltip showing last opened date |
| Clicks | Click count with tooltip showing last clicked date |
| Sent At | Relative time with exact date tooltip |

### Email Detail View

View full email details in a slideOver modal with three sections:

**General**
- Sender Info tab: from, to, cc, bcc, reply-to, subject
- Statistics tab: open/click counts, timestamps for each delivery stage
- Events tab: chronological timeline of all webhook events

**Content**
- Preview tab: rendered HTML in an iframe
- HTML tab: raw HTML source with copy button
- Text tab: plain text version with copy button

**Attachments**
- File metadata (name, size, MIME type)
- Download links

### Actions

| Action | Type | Description |
|--------|------|-------------|
| **View** | Record | Open email detail in a slideOver modal |
| **Resend** | Record | Resend email to custom recipients (to, cc, bcc) |
| **Bulk Resend** | Bulk | Resend selected emails, pre-filled with original recipients |
| **Send Test Email** | Header | Send a simple or attachment test email to verify setup |
| **Delete** | Bulk | Delete selected email records |

### Stats Widget

The dashboard widget shows four metrics as percentages:

| Stat | Color | Description |
|------|-------|-------------|
| Delivered | Green | Delivery rate |
| Opened | Blue | Open rate |
| Clicked | Teal | Click rate |
| Bounced | Red | Bounce rate (soft + hard) |

Each stat links to its corresponding filter tab. Toggle visibility with `'view_any' => false`.

## Extending the Package

### Custom Models

Extend the base models and update the config:

```php
use Basement\BetterMails\Core\Models\BetterEmail;

class Email extends BetterEmail
{
    // Add custom relationships, scopes, accessors, etc.
}
```

```php
// config/filament-better-mails.php
'models' => [
    'mail' => \App\Models\Email::class,
],
```

### Custom Webhook Providers

1. Create a driver implementing `BetterDriverContract`:

```php
use Basement\BetterMails\Core\AbstractMailDriver;

class PostmarkDriver extends AbstractMailDriver
{
    public function handle(array $data): void
    {
        // Parse webhook payload and dispatch events
    }
}
```

2. Add the provider enum case to `SupportedMailProvidersEnum`

3. Register in config:

```php
'webhooks' => [
    'provider' => 'postmark',
    'drivers' => [
        'postmark' => [
            'driver' => \App\Mail\Drivers\PostmarkDriver::class,
            'key_secret' => env('POSTMARK_WEBHOOK_SECRET'),
        ],
    ],
],
```

### Polymorphic Relationships

The package creates a `mailables` polymorph table for associating emails with any model:

```php
// The migration creates:
// mailables (configurable) -> id, mail_id (FK), mailable_type, mailable_id
```

### Publishing Views

Customize the Blade templates:

```bash
php artisan vendor:publish --tag="filament-better-mails-views"
```

| View | Purpose |
|------|---------|
| `preview.blade.php` | Email preview iframe wrapper |
| `html.blade.php` | HTML content display |
| `mails/html.blade.php` | HTML source with syntax highlighting |
| `mails/preview.blade.php` | Iframe preview component |
| `mails/download.blade.php` | Attachment download button |
| `mails/test/simple.blade.php` | Simple test email template |
| `mails/test/attachment.blade.php` | Attachment test email template |
| `tables/columns/mail-status.blade.php` | Status badge column |

## Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `MAIL_MAILER` | `smtp` | Laravel mail driver (set to `resend`) |
| `RESEND_API_KEY` | -- | Resend API key |
| `RESEND_WEBHOOK_SECRET` | -- | Resend webhook signing secret |
| `MAILS_WEBHOOK_PROVIDER` | `resend` | Active webhook provider |
| `MAILS_WEBHOOK_LOGGING_ENABLED` | `false` | Enable webhook debug logging |
| `MAILS_WEBHOOK_LOG_CHANNEL` | -- | Custom log channel for webhooks |
| `MAILS_LOGGING_ATTACHMENTS_ENABLED` | `true` | Store email attachments to disk |
| `FILESYSTEM_DISK` | `local` | Storage disk for attachments |

## Supported Providers

| Provider | Status |
|----------|--------|
| [Resend](https://resend.com) | Supported |

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
