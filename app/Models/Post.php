<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

final class Post extends Content
{
    protected static function booted(): void
    {
        static::addGlobalScope('post_type', fn (Builder $builder) => $builder->where('type', self::TYPE_POST));
        static::creating(fn (Post $post) => $post->type = self::TYPE_POST);
    }
}
