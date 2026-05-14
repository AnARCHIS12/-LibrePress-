<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Illuminate\Support\Str;

final class BlockEditorMapper
{
    /**
     * @param array<string, mixed>|null $document
     * @return list<array{type: string, data: array<string, mixed>}>
     */
    public static function fromDocument(?array $document): array
    {
        $blocks = is_array($document['blocks'] ?? null) ? $document['blocks'] : [];

        return collect($blocks)
            ->map(function (mixed $block): ?array {
                if (! is_array($block)) {
                    return null;
                }

                $type = (string) ($block['type'] ?? 'core/paragraph');
                $props = is_array($block['props'] ?? null) ? $block['props'] : [];

                return [
                    'type' => Str::after($type, 'core/'),
                    'data' => $props,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param list<array{type?: string, data?: array<string, mixed>}>|null $items
     * @return array{version: int, blocks: list<array{id: string, type: string, props: array<string, mixed>}>}
     */
    public static function toDocument(?array $items): array
    {
        $blocks = collect($items ?? [])
            ->map(function (mixed $item, int $index): ?array {
                if (! is_array($item)) {
                    return null;
                }

                $type = (string) ($item['type'] ?? 'paragraph');
                $data = is_array($item['data'] ?? null) ? $item['data'] : [];

                return [
                    'id' => $data['id'] ?? 'block-'.($index + 1),
                    'type' => str_starts_with($type, 'core/') ? $type : 'core/'.$type,
                    'props' => self::cleanData($data),
                ];
            })
            ->filter()
            ->values()
            ->all();

        return [
            'version' => 1,
            'blocks' => $blocks,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private static function cleanData(array $data): array
    {
        unset($data['id']);

        if (isset($data['columns']) && is_array($data['columns'])) {
            $data['columns'] = array_values(array_filter($data['columns'], fn (mixed $value): bool => filled($value)));
        }

        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = array_values(array_filter($data['images'], fn (mixed $image): bool => is_array($image) && filled($image['src'] ?? null)));
        }

        return $data;
    }
}
