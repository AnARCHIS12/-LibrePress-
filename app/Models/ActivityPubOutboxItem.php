<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ActivityPubOutboxItem extends Model
{
    protected $table = 'activitypub_outbox';

    protected $fillable = [
        'actor_id',
        'payload',
        'status',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'delivered_at' => 'datetime',
        ];
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(ActivityPubActor::class, 'actor_id');
    }
}
