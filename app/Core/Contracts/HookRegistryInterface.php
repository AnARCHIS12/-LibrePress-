<?php

declare(strict_types=1);

namespace App\Core\Contracts;

interface HookRegistryInterface
{
    public function action(string $name, callable $listener, int $priority = 10): void;

    /**
     * @template T
     * @param T $value
     * @return T
     */
    public function applyFilters(string $name, mixed $value, mixed ...$payload): mixed;

    public function filter(string $name, callable $listener, int $priority = 10): void;

    public function doAction(string $name, mixed ...$payload): void;
}

