<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Module extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'version',
        'enabled',
        'manifest',
        'installed_at',
        'enabled_at',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'manifest' => 'array',
            'installed_at' => 'datetime',
            'enabled_at' => 'datetime',
        ];
    }
}

