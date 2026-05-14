<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ContentRevision extends Model
{
    protected $fillable = [
        'content_id',
        'user_id',
        'title',
        'body_json',
        'body_html',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'body_json' => 'array',
            'meta' => 'array',
        ];
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

