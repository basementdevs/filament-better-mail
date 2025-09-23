<?php

namespace Basement\BetterMails\Core\Models;

use Basement\BetterMails\Core\Enums\MailEventType;
use Basement\BetterMails\Database\Factories\BetterMailFactory;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $mail_id
 * @property MailEventType $type
 * @property string|null $ip_address
 * @property string|null $hostname
 * @property string|null $platform
 * @property string|null $os
 * @property string|null $browser
 * @property string|null $user_agent
 * @property string|null $city
 * @property string|null $country_code
 * @property string|null $link
 * @property string|null $tag
 * @property object|null $payload
 * @property CarbonInterface|null $occurred_at
 * @property CarbonInterface|null $unsuppressed_at
 */
class BetterEmailEvent extends Model
{
    use HasFactory;

    protected $table = 'mail_events';

    protected $fillable = [
        'mail_id',
        'type',
        'ip_address',
        'hostname',
        'platform',
        'os',
        'browser',
        'user_agent',
        'city',
        'country_code',
        'link',
        'tag',
        'payload',
        'occurred_at',
        'unsuppressed_at',
    ];

    protected $casts = [
        'type' => MailEventType::class,
        'payload' => 'object',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'occurred_at' => 'datetime',
        'unsuppressed_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<BetterEmail, $this>
     */
    public function mail(): BelongsTo
    {
        return $this->belongsTo(BetterEmail::class, 'mail_id');
    }

    protected static function newFactory(): BetterMailFactory
    {
        return BetterMailFactory::new();
    }
}
