<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ActivityPubFollower extends Model
{
    protected $table = 'activitypub_followers';

    protected $fillable = [
        'actor_id',
        'remote_actor',
        'inbox_url',
        'status',
    ];
}

