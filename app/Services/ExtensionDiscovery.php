<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\File;

final class ExtensionDiscovery
{
    /**
     * @return list<array<string, mixed>>
     */
    public function modules(): array
    {
        return $this->discover(base_path('modules/*/module.json'));
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function themes(): array
    {
        return $this->discover(base_path('themes/*/theme.json'));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function discover(string $pattern): array
    {
        return collect(glob($pattern) ?: [])
            ->map(fn (string $file): array => [
                ...json_decode(File::get($file), true, flags: JSON_THROW_ON_ERROR),
                'path' => dirname($file),
            ])
            ->sortBy('name')
            ->values()
            ->all();
    }
}

