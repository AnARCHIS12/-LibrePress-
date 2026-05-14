<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Redirect extends Model
{
    protected $fillable = [
        'source_path',
        'target_path',
        'status_code',
    ];
}

