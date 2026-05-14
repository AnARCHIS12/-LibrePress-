<?php

declare(strict_types=1);

namespace App\Core\Blocks;

final class BlockSchema
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function definitions(): array
    {
        return [
            'core/markdown' => ['label' => 'Markdown', 'props' => ['text' => 'string']],
            'core/heading' => ['label' => 'Titre', 'props' => ['text' => 'string', 'level' => 'int']],
            'core/paragraph' => ['label' => 'Paragraphe', 'props' => ['text' => 'string']],
            'core/quote' => ['label' => 'Citation', 'props' => ['text' => 'string', 'cite' => 'string']],
            'core/code' => ['label' => 'Code', 'props' => ['code' => 'string', 'language' => 'string']],
            'core/button' => ['label' => 'Bouton', 'props' => ['label' => 'string', 'url' => 'string']],
            'core/image' => ['label' => 'Image', 'props' => ['src' => 'string', 'alt' => 'string']],
            'core/embed' => ['label' => 'Embed', 'props' => ['url' => 'string']],
            'core/columns' => ['label' => 'Colonnes', 'props' => ['columns' => 'array']],
        ];
    }

    public function has(string $type): bool
    {
        return array_key_exists($type, $this->definitions());
    }
}

