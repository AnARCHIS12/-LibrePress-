<?php

declare(strict_types=1);

namespace App\Services;

use Composer\Semver\Semver;

final class ExtensionSecurity
{
    /**
     * @param array<string, mixed> $manifest
     */
    public function checksum(array $manifest): string
    {
        return hash_file('sha256', (string) $manifest['manifest_file']);
    }

    /**
     * @param array<string, mixed> $manifest
     */
    public function isCompatible(array $manifest): bool
    {
        $constraint = (string) ($manifest['core'] ?? '^0.1');
        $version = (string) config('librepress.version', '0.1.0');

        return Semver::satisfies($version, $constraint);
    }
}

