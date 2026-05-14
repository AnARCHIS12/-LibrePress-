<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Services\BlockRenderer;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class ContentPreviewController
{
    public function __construct(private BlockRenderer $renderer)
    {
    }

    public function __invoke(Content $content): View
    {
        Gate::authorize('update', $content);

        return view($content->type === 'post' ? 'front.post' : 'front.page', [
            'content' => $content->load(['comments' => fn ($query) => $query->where('status', 'approved')->latest()]),
            'renderedBlocks' => $this->renderer->render($content),
            'title' => '[Preview] '.$content->title,
            'description' => $content->excerpt,
        ]);
    }
}

