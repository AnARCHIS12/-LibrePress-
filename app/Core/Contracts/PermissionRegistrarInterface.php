<?php

declare(strict_types=1);

namespace App\Core\Contracts;

interface PermissionRegistrarInterface
{
    /**
     * @param list<string> $permissions
     */
    public function register(string $owner, array $permissions): void;
}

