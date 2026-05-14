<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'sort_order',
        'new_tab',
    ];

    protected function casts(): array
    {
        return [
            'new_tab' => 'boolean',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}

