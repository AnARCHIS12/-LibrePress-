<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Contracts\HookRegistryInterface;
use App\Core\Hooks\InMemoryHookRegistry;
use App\Models\Content;
use App\Services\BlockRenderer;
use PHPUnit\Framework\TestCase;

final class BlockRendererTest extends TestCase
{
    public function test_renders_native_blocks_safely(): void
    {
        $content = new Content([
            'body_json' => [
                'version' => 1,
                'blocks' => [
                    ['type' => 'core/heading', 'props' => ['text' => '<script>X</script>', 'level' => 2]],
                    ['type' => 'core/button', 'props' => ['label' => 'Go', 'url' => 'javascript:alert(1)']],
                    ['type' => 'core/code', 'props' => ['code' => '<b>x</b>', 'language' => 'php']],
                ],
            ],
        ]);

        $renderer = new BlockRenderer(new InMemoryHookRegistry());
        $html = $renderer->render($content);

        $this->assertStringContainsString('&lt;script&gt;X&lt;/script&gt;', $html);
        $this->assertStringContainsString('href="#"', $html);
        $this->assertStringContainsString('&lt;b&gt;x&lt;/b&gt;', $html);
    }
}

