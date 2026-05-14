<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class SearchDocument extends Model
{
    protected $fillable = [
        'searchable_type',
        'searchable_id',
        'type',
        'locale',
        'title',
        'excerpt',
        'body',
        'meta',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function searchable(): MorphTo
    {
        return $this->morphTo();
    }
}

