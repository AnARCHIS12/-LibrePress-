<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

final class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'disk',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'alt',
        'caption',
        'hash',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}

