<?php

declare(strict_types=1);

namespace App\Filament\Support;

final class JsonState
{
    public static function encode(mixed $state): string
    {
        if (is_string($state)) {
            return $state;
        }

        return json_encode($state ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
    }

    /**
     * @return array<string, mixed>
     */
    public static function decode(mixed $state): array
    {
        if (is_array($state)) {
            return $state;
        }

        $decoded = json_decode((string) $state, true);

        return is_array($decoded) ? $decoded : [];
    }
}
