<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

final class Page extends Content
{
    protected static function booted(): void
    {
        static::addGlobalScope('page_type', fn (Builder $builder) => $builder->where('type', self::TYPE_PAGE));
        static::creating(fn (Page $page) => $page->type = self::TYPE_PAGE);
    }
}

