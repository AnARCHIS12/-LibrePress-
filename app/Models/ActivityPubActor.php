<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ActivityPubActor extends Model
{
    protected $table = 'activitypub_actors';

    protected $fillable = [
        'user_id',
        'username',
        'type',
        'public_key',
        'private_key',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outbox(): HasMany
    {
        return $this->hasMany(ActivityPubOutboxItem::class, 'actor_id');
    }
}

