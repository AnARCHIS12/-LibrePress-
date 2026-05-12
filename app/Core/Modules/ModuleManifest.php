<?php

declare(strict_types=1);

namespace App\Core\Modules;

final readonly class ModuleManifest
{
    /**
     * @param list<string> $permissions
     * @param list<string> $dependencies
     */
    public function __construct(
        public string $name,
        public string $slug,
        public string $version,
        public string $entry,
        public array $permissions = [],
        public array $dependencies = [],
    ) {
    }

    /**
     * @param array{name:string,slug:string,version:string,entry:string,permissions?:list<string>,dependencies?:list<string>} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'],
            version: $data['version'],
            entry: $data['entry'],
            permissions: $data['permissions'] ?? [],
            dependencies: $data['dependencies'] ?? [],
        );
    }
}

