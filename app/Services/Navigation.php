<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class Navigation
{
    /**
     * @return Collection<int, \App\Models\MenuItem>
     */
    public function items(string $location = 'primary'): Collection
    {
        return Cache::remember("navigation.$location", 300, fn () => Menu::query()
            ->where('location', $location)
            ->with('items')
            ->first()
            ?->items ?? collect());
    }
}

