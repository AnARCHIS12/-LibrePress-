<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Content extends Model
{
    public const TYPE_PAGE = 'page';
    public const TYPE_POST = 'post';

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

    public function revisions(): HasMany
    {
        return $this->hasMany(ContentRevision::class);
    }

    public function searchDocument(): MorphOne
    {
        return $this->morphOne(SearchDocument::class, 'searchable');
    }

    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'content_terms');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
