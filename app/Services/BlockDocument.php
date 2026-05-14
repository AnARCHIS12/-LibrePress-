<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Blocks\BlockSchema;
use Illuminate\Support\Str;
use InvalidArgumentException;

final readonly class BlockDocument
{
    public function __construct(private BlockSchema $schema)
    {
    }

    /**
     * @return array{version:int,blocks:list<array<string,mixed>>}
     */
    public function fromMarkdown(string $markdown): array
    {
        return [
            'version' => 1,
            'blocks' => [[
                'id' => (string) Str::uuid(),
                'type' => 'core/markdown',
                'props' => ['text' => $markdown],
            ]],
        ];
    }

    /**
     * @return array{version:int,blocks:list<array<string,mixed>>}
     */
    public function fromJson(?string $json, string $fallbackMarkdown): array
    {
        if (blank($json)) {
            return $this->fromMarkdown($fallbackMarkdown);
        }

        $document = json_decode((string) $json, true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($document) || ! isset($document['blocks']) || ! is_array($document['blocks'])) {
            throw new InvalidArgumentException('Document de blocs invalide.');
        }

        $blocks = [];

        foreach ($document['blocks'] as $block) {
            if (! is_array($block)) {
                continue;
            }

            $type = (string) ($block['type'] ?? 'core/markdown');

            if (! $this->schema->has($type)) {
                $type = 'core/markdown';
            }

            $blocks[] = [
                'id' => (string) ($block['id'] ?? Str::uuid()),
                'type' => $type,
                'props' => is_array($block['props'] ?? null) ? $block['props'] : [],
            ];
        }

        return ['version' => 1, 'blocks' => $blocks];
    }
}

