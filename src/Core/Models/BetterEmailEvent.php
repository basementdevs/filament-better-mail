<?php

namespace Basement\BetterMails\Core\Models;

use Basement\BetterMails\Core\Enums\MailEventTypeEnum;
use Basement\BetterMails\Database\Factories\BetterMailFactory;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $mail_id
 * @property MailEventTypeEnum $type
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
        'type' => MailEventTypeEnum::class,
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

    #[Scope]
    protected function softBounced(Builder $query): Builder
    {
        return $query->where('type', MailEventTypeEnum::SoftBounced);
    }

    #[Scope]
    protected function hardBounced(Builder $query): Builder
    {
        return $query->where('type', MailEventTypeEnum::HardBounced);
    }

    #[Scope]
    protected function opened(Builder $query): Builder
    {
        return $query->where('type', MailEventTypeEnum::Opened);
    }

    #[Scope]
    protected function delivered(Builder $query): Builder
    {
        return $query->where('type', MailEventTypeEnum::Delivered);
    }

    #[Scope]
    protected function clicked(Builder $query): Builder
    {
        return $query->where('type', MailEventTypeEnum::Clicked);
    }
}
