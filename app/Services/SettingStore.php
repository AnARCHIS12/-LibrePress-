<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

final class SettingStore
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting.$key", fn () => Setting::query()
            ->where('key', $key)
            ->value('value')['value'] ?? $default);
    }

    public function set(string $key, mixed $value, string $scope = 'site'): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => ['value' => $value],
                'type' => get_debug_type($value),
                'scope' => $scope,
                'autoload' => true,
            ],
        );

        Cache::forget("setting.$key");
    }
}

