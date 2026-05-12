<?php

declare(strict_types=1);

namespace App\Core\Hooks;

use App\Core\Contracts\HookRegistryInterface;

final class InMemoryHookRegistry implements HookRegistryInterface
{
    /** @var array<string, array<int, list<callable>>> */
    private array $actions = [];

    /** @var array<string, array<int, list<callable>>> */
    private array $filters = [];

    public function action(string $name, callable $listener, int $priority = 10): void
    {
        $this->actions[$name][$priority][] = $listener;
    }

    public function filter(string $name, callable $listener, int $priority = 10): void
    {
        $this->filters[$name][$priority][] = $listener;
    }

    public function doAction(string $name, mixed ...$payload): void
    {
        foreach ($this->listenersFor($this->actions, $name) as $listener) {
            $listener(...$payload);
        }
    }

    public function applyFilters(string $name, mixed $value, mixed ...$payload): mixed
    {
        foreach ($this->listenersFor($this->filters, $name) as $listener) {
            $value = $listener($value, ...$payload);
        }

        return $value;
    }

    /**
     * @param array<string, array<int, list<callable>>> $registry
     * @return list<callable>
     */
    private function listenersFor(array $registry, string $name): array
    {
        $priorities = $registry[$name] ?? [];
        ksort($priorities);

        return array_merge(...array_values($priorities ?: [[]]));
    }
}

