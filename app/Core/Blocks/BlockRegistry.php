<?php

declare(strict_types=1);

namespace App\Core\Blocks;

final class BlockRegistry
{
    /** @var array<string, BlockDefinition> */
    private array $blocks = [];

    public function register(BlockDefinition $definition): void
    {
        $this->blocks[$definition->name] = $definition;
    }

    public function get(string $name): ?BlockDefinition
    {
        return $this->blocks[$name] ?? null;
    }

    /**
     * @return array<string, BlockDefinition>
     */
    public function all(): array
    {
        return $this->blocks;
    }
}

