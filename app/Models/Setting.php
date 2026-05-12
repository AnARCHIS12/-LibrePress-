<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'scope',
        'autoload',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
            'autoload' => 'boolean',
        ];
    }
}

