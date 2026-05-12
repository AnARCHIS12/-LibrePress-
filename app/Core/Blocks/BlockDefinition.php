<?php

declare(strict_types=1);

namespace App\Core\Blocks;

final readonly class BlockDefinition
{
    /**
     * @param array<string, mixed> $schema
     */
    public function __construct(
        public string $name,
        public string $label,
        public string $editorComponent,
        public string $renderView,
        public array $schema,
    ) {
    }
}

