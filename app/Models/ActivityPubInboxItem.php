<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ActivityPubInboxItem extends Model
{
    protected $table = 'activitypub_inbox';

    protected $fillable = [
        'actor_id',
        'remote_actor',
        'payload',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}

