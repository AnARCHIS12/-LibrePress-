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
            'core/heading' => $this->heading($props),
            'core/paragraph' => '<p>'.e((string) ($props['text'] ?? '')).'</p>',
            'core/quote' => $this->quote($props),
            'core/code' => $this->code($props),
            'core/button' => $this->button($props),
            'core/image' => $this->image($props),
            'core/gallery' => $this->gallery($props),
            'core/embed' => $this->embed($props),
            'core/columns' => $this->columns($props),
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
    private function heading(array $props): string
    {
        $level = max(2, min(4, (int) ($props['level'] ?? 2)));

        return "<h{$level}>".e((string) ($props['text'] ?? ''))."</h{$level}>";
    }

    /**
     * @param array<string, mixed> $props
     */
    private function quote(array $props): string
    {
        $cite = trim((string) ($props['cite'] ?? ''));
        $footer = $cite !== '' ? '<footer>'.e($cite).'</footer>' : '';

        return '<blockquote><p>'.e((string) ($props['text'] ?? ''))."</p>{$footer}</blockquote>";
    }

    /**
     * @param array<string, mixed> $props
     */
    private function code(array $props): string
    {
        $language = preg_replace('/[^a-z0-9_-]/i', '', (string) ($props['language'] ?? 'text')) ?: 'text';

        return '<pre><code class="language-'.$language.'">'.e((string) ($props['code'] ?? '')).'</code></pre>';
    }

    /**
     * @param array<string, mixed> $props
     */
    private function button(array $props): string
    {
        $url = $this->safeUrl((string) ($props['url'] ?? '#'));
        $label = e((string) ($props['label'] ?? 'Lire'));

        return '<p><a class="button primary" href="'.e($url).'">'.$label.'</a></p>';
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

    /**
     * @param array<string, mixed> $props
     */
    private function gallery(array $props): string
    {
        $images = is_array($props['images'] ?? null) ? $props['images'] : [];
        $html = collect($images)
            ->map(function (mixed $image): string {
                if (! is_array($image)) {
                    return '';
                }

                return $this->image([
                    'src' => $image['src'] ?? '',
                    'alt' => $image['alt'] ?? '',
                ]);
            })
            ->filter()
            ->implode('');

        return $html === '' ? '' : '<div class="gallery">'.$html.'</div>';
    }

    /**
     * @param array<string, mixed> $props
     */
    private function embed(array $props): string
    {
        $url = $this->safeUrl((string) ($props['url'] ?? ''));

        return $url === '' ? '' : '<p><a href="'.e($url).'" rel="noopener noreferrer">'.e($url).'</a></p>';
    }

    /**
     * @param array<string, mixed> $props
     */
    private function columns(array $props): string
    {
        $columns = is_array($props['columns'] ?? null) ? $props['columns'] : [];
        $html = collect($columns)
            ->map(fn (mixed $text): string => '<div>'.Str::markdown((string) $text, ['html_input' => 'strip']).'</div>')
            ->implode('');

        return '<div class="grid">'.$html.'</div>';
    }

    private function safeUrl(string $url): string
    {
        if ($url === '' || str_starts_with($url, '/')) {
            return $url;
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : '#';
    }
}
