<?php

declare(strict_types=1);

namespace App\Core\Contracts;

interface ThemeInterface
{
    public function slug(): string;

    public function version(): string;

    /**
     * @return array<string, string>
     */
    public function regions(): array;

    public function viewFor(string $template): string;
}

