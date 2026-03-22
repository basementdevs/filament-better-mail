<?php

namespace Basement\BetterMails\Core\Models;

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Database\Factories\BetterMailFactory;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int|null $id
 * @property string|null $uuid
 * @property string|null $mailer
 * @property string|null $transport
 * @property string|null $stream_id
 * @property string|null $mail_class
 * @property string|null $subject
 * @property string|null $html
 * @property string|null $text
 * @property array|null $from
 * @property array|null $reply_to
 * @property array|null $to
 * @property array|null $cc
 * @property array|null $bcc
 * @property int|null $opens
 * @property int|null $clicks
 * @property array|null $tags
 * @property CarbonInterface|null $sent_at
 * @property CarbonInterface|null $resent_at
 * @property CarbonInterface|null $accepted_at
 * @property CarbonInterface|null $delivered_at
 * @property CarbonInterface|null $last_opened_at
 * @property CarbonInterface|null $last_clicked_at
 * @property CarbonInterface|null $complained_at
 * @property CarbonInterface|null $soft_bounced_at
 * @property CarbonInterface|null $hard_bounced_at
 * @property CarbonInterface|null $unsubscribed_at
 * @property CarbonInterface|null $scheduled_at
 * @property CarbonInterface|null $suppressed_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
class BetterEmail extends Model
{
    use HasFactory;
    use MassPrunable;

    protected $table = 'mails';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('filament-better-mails.mails.database.tables.mails') ?: parent::getTable();
    }

    protected $fillable = [
        'uuid',
        'mailer',
        'transport',
        'stream_id',
        'mail_class',
        'subject',
        'html',
        'text',
        'from',
        'reply_to',
        'to',
        'cc',
        'bcc',
        'opens',
        'clicks',
        'tags',
        'sent_at',
        'resent_at',
        'delivered_at',
        'last_opened_at',
        'last_clicked_at',
        'complained_at',
        'soft_bounced_at',
        'hard_bounced_at',
        'accepted_at',
        'unsubscribed_at',
        'scheduled_at',
        'suppressed_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'mailer' => 'string',
        'transport' => 'string',
        'stream_id' => 'string',
        'subject' => 'string',
        'from' => 'array',
        'reply_to' => 'array',
        'to' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
        'opens' => 'integer',
        'clicks' => 'integer',
        'tags' => 'json',
        'sent_at' => 'datetime',
        'resent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'delivered_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'last_clicked_at' => 'datetime',
        'complained_at' => 'datetime',
        'soft_bounced_at' => 'datetime',
        'hard_bounced_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'suppressed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function prunable(): Builder
    {
        $pruneAfter = config('filament-better-mails.mails.database.pruning.after', 30);

        return static::query()->where('created_at', '<=', now()->subDays($pruneAfter));
    }

    public function latestEvent(): HasOne
    {
        return $this->hasOne(config('filament-better-mails.mails.models.event'), 'mail_id')->latestOfMany('occurred_at');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(config('filament-better-mails.mails.models.attachment'), 'mail_id');
    }

    public function events(): HasMany
    {
        return $this
            ->hasMany(config('filament-better-mails.mails.models.event'), 'mail_id')
            ->orderBy('occurred_at', 'desc');
    }

    public function sent(): void
    {
        $this->update(['sent_at' => now()]);
    }

    public function delivered(): void
    {
        $this->update(['delivered_at' => now()]);
    }

    public function opened(): void
    {
        $this->update([
            'last_opened_at' => now(),
            'opens' => $this->opens + 1,
        ]);
    }

    public function clicked(): void
    {
        $this->update([
            'last_clicked_at' => now(),
            'clicks' => $this->clicks + 1,
        ]);
    }

    public function complained(): void
    {
        $this->update(['complained_at' => now()]);
    }

    public function softBounced(): void
    {
        $this->update(['soft_bounced_at' => now()]);
        $this->events()->create([
            'type' => MailEventTypeEnum::SoftBounced,
            'occurred_at' => now(),
        ]);
    }

    public function accepted(): void
    {
        $this->update(['accepted_at' => now()]);
    }

    public function unsubscribed(): void
    {
        $this->update(['unsubscribed_at' => now()]);
    }

    public function hardBounced(): void
    {
        $this->update(['hard_bounced_at' => now()]);
    }

    public function scheduled(): void
    {
        $this->update(['scheduled_at' => now()]);
    }

    public function suppressed(): void
    {
        $this->update(['suppressed_at' => now()]);
    }

    protected static function newFactory(): BetterMailFactory
    {
        return BetterMailFactory::new();
    }
}
