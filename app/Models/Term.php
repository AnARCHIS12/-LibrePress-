<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Term extends Model
{
    protected $fillable = [
        'taxonomy_id',
        'parent_id',
        'name',
        'slug',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class, 'content_terms');
    }
}

