<?php

declare(strict_types=1);

namespace Modules\Blog;

use App\Core\Contracts\ModuleContext;
use App\Core\Contracts\ModuleInterface;

final class BlogModule implements ModuleInterface
{
    public function slug(): string
    {
        return 'blog';
    }

    public function version(): string
    {
        return '0.1.0';
    }

    public function boot(ModuleContext $context): void
    {
        $context->permissions->register($this->slug(), [
            'blog.posts.view',
            'blog.posts.create',
            'blog.posts.update',
            'blog.posts.delete',
            'blog.posts.publish',
        ]);

        $context->hooks->filter('seo.meta', [$this, 'addPostMeta']);
    }

    public function enable(ModuleContext $context): void
    {
        $context->hooks->doAction('module.enabled', $this->slug());
    }

    public function disable(ModuleContext $context): void
    {
        $context->hooks->doAction('module.disabled', $this->slug());
    }

    /**
     * @param array<string, mixed> $meta
     * @return array<string, mixed>
     */
    public function addPostMeta(array $meta): array
    {
        $meta['type'] ??= 'article';

        return $meta;
    }
}

