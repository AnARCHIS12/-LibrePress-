<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CommentReport extends Model
{
    protected $fillable = [
        'comment_id',
        'reason',
        'message',
        'ip_hash',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}

