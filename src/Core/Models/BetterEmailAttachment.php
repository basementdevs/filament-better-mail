<?php

namespace Basement\BetterMails\Core\Models;

use Backstage\Mails\Database\Factories\MailAttachmentFactory;
use Backstage\Mails\Models\Mail;
use Basement\BetterMails\Database\Factories\BetterEmailAttachmentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BetterEmailAttachment extends Model
{
    use HasFactory;

    /**
     * @property-read string $disk
     * @property-read string $uuid
     * @property-read string $filename
     * @property-read string $mime
     * @property-read bool $inline
     * @property-read int $size
     * @property-read BetterEmail $mail
     */
    protected $table = 'mail_attachments';
    protected $fillable = [
        'disk',
        'uuid',
        'filename',
        'mime',
        'inline',
        'size',
    ];

    protected $casts = [
        'disk' => 'string',
        'uuid' => 'string',
        'filename' => 'string',
        'mime' => 'string',
        'inline' => 'boolean',
        'size' => 'integer',
    ];

    public function getTable()
    {
        return config('filament-better-mails.mails.database.tables.attachments');
    }

    protected static function newFactory(): Factory
    {
        return BetterEmailAttachmentFactory::new();
    }

    public function mail(): BelongsTo
    {
        return $this->belongsTo(config('filament-better-mails.mails.models.mail'));
    }

    public function getStoragePathAttribute(): string
    {
        return rtrim(config('filament-better-mails.logging.attachments.root'), '/').'/'.$this->getKey().'/'.$this->filename;
    }

    public function getFileDataAttribute(): string
    {
        return Storage::disk($this->disk)->get($this->storagePath);
    }

    public function downloadFileFromStorage(?string $filename = null): string
    {
        return Storage::disk($this->disk)
            ->download(
                $this->storagePath,
                $filename ?? $this->filename, [
                'Content-Type' => $this->mime,
            ]);
    }
}
