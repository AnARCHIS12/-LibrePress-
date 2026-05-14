<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Content;
use App\Services\BlockRenderer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

final readonly class FrontController
{
    public function __construct(private BlockRenderer $renderer)
    {
    }

    public function home(): View
    {
        $content = Content::query()
            ->where('type', 'page')
            ->where('slug', 'accueil')
            ->where('status', 'published')
            ->first();

        $posts = Content::query()
            ->where('type', 'post')
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(6)
            ->get();

        return view('front.home', [
            'content' => $content,
            'posts' => $posts,
            'renderedBlocks' => $content ? $this->renderer->render($content) : '',
        ]);
    }

    public function blog(): View
    {
        $posts = Content::query()
            ->where('type', 'post')
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(12);

        return view('front.blog', ['posts' => $posts]);
    }

    public function show(string $slug): View
    {
        $content = Cache::remember("content.public.$slug", 300, fn () => Content::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail());

        return view($content->type === 'post' ? 'front.post' : 'front.page', [
            'content' => $content->load(['comments' => fn ($query) => $query->where('status', 'approved')->latest()]),
            'renderedBlocks' => $this->renderer->render($content),
            'title' => data_get($content->meta, 'seo.title', $content->title),
            'description' => data_get($content->meta, 'seo.description', $content->excerpt),
        ]);
    }

    public function rss(): Response
    {
        $posts = Content::query()
            ->where('type', 'post')
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()
            ->view('front.rss', ['posts' => $posts])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    public function atom(): Response
    {
        $posts = Content::query()
            ->where('type', 'post')
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()
            ->view('front.atom', ['posts' => $posts])
            ->header('Content-Type', 'application/atom+xml; charset=UTF-8');
    }

    public function sitemap(): Response
    {
        $contents = Content::query()
            ->where('status', 'published')
            ->latest('updated_at')
            ->limit(1000)
            ->get();

        return response()
            ->view('front.sitemap', ['contents' => $contents])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        return response()
            ->view('front.robots')
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function activityActor(string $username): Response
    {
        $payload = [
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Person',
            'id' => url("/@$username"),
            'preferredUsername' => $username,
            'inbox' => url("/api/v1/activitypub/$username/inbox"),
            'outbox' => url("/api/v1/activitypub/$username/outbox"),
        ];

        return response()
            ->json($payload)
            ->header('Content-Type', 'application/activity+json');
    }
}
