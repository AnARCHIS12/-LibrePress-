<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ImportRun extends Model
{
    protected $fillable = [
        'source',
        'file_path',
        'status',
        'summary',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'summary' => 'array',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

