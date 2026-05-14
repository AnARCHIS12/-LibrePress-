<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Taxonomy extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'owner',
    ];

    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }
}

