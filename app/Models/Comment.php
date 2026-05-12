<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Comment extends Model
{
    protected $fillable = [
        'content_id',
        'user_id',
        'author_name',
        'author_email_hash',
        'body',
        'status',
        'ip_hash',
        'user_agent_hash',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}

