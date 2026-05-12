<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Contracts\HookRegistryInterface;
use App\Models\Content;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

final readonly class BlockRenderer
{
    public function __construct(private HookRegistryInterface $hooks)
    {
    }

    public function render(Content $content): string
    {
        $blocks = $content->body_json['blocks'] ?? [];
        $html = collect($blocks)
            ->map(fn (array $block): string => $this->renderBlock($block))
            ->implode("\n");

        return (string) $this->hooks->applyFilters('render.content.html', $html, $content);
    }

    /**
     * @param array<string, mixed> $block
     */
    private function renderBlock(array $block): string
    {
        $type = $block['type'] ?? 'core/markdown';
        $props = $block['props'] ?? [];

        $html = match ($type) {
            'core/heading' => '<h2>'.e((string) ($props['text'] ?? '')).'</h2>',
            'core/image' => $this->image($props),
            default => (string) Str::markdown((string) ($props['text'] ?? ''), [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]),
        };

        return (string) $this->hooks->applyFilters('render.block.html', $html, $block);
    }

    /**
     * @param array<string, mixed> $props
     */
    private function image(array $props): string
    {
        $src = e((string) ($props['src'] ?? ''));
        $alt = e((string) ($props['alt'] ?? ''));

        return $src === '' ? '' : "<img src=\"{$src}\" alt=\"{$alt}\" loading=\"lazy\">";
    }
}

