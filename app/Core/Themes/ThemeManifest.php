<?php

declare(strict_types=1);

namespace App\Core\Themes;

final readonly class ThemeManifest
{
    /**
     * @param array<string, string> $regions
     * @param array<string, string> $templates
     */
    public function __construct(
        public string $name,
        public string $slug,
        public string $version,
        public array $regions,
        public array $templates,
    ) {
    }
}

