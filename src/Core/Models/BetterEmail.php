<?php

namespace Basement\BetterMails\Core\Models;

use Basement\BetterMails\Database\Factories\BetterMailFactory;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
class BetterEmail extends Model
{
    use HasFactory;
    use MassPrunable;

    protected $table = 'mails';

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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function prunable(): Builder
    {
        $pruneAfter = config('filament-better-mails.mails.database.pruning.after', 30);

        return static::query()->where('created_at', '<=', now()->subDays($pruneAfter));
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(config('filament-better-mails.mails.models.attachment'), 'mail_id');
    }

    public function events(): HasMany
    {
        return $this
            ->hasMany(BetterEmailEvent::class, 'mail_id')
            ->orderBy('occurred_at', 'desc');
    }

    public function sent(): void
    {
        $this->update(['sent_at' => now()]);
    }

    protected static function newFactory(): BetterMailFactory
    {
        return BetterMailFactory::new();
    }
}
