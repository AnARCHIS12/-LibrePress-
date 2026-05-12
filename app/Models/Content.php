<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Content extends Model
{
    protected $fillable = [
        'type',
        'status',
        'author_id',
        'parent_id',
        'slug',
        'title',
        'excerpt',
        'body_json',
        'body_html',
        'locale',
        'published_at',
        'scheduled_at',
        'meta',
    ];

    protected $casts = [
        'body_json' => 'array',
        'meta' => 'array',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
