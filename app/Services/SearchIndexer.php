<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Content;
use App\Models\SearchDocument;

final class SearchIndexer
{
    public function index(Content $content): void
    {
        if ($content->status !== 'published') {
            $this->remove($content);

            return;
        }

        SearchDocument::query()->updateOrCreate(
            [
                'searchable_type' => Content::class,
                'searchable_id' => $content->id,
            ],
            [
                'type' => $content->type,
                'locale' => $content->locale,
                'title' => $content->title,
                'excerpt' => $content->excerpt,
                'body' => strip_tags((string) $content->body_html),
                'meta' => ['slug' => $content->slug],
                'published_at' => $content->published_at,
            ],
        );
    }

    public function remove(Content $content): void
    {
        SearchDocument::query()
            ->where('searchable_type', Content::class)
            ->where('searchable_id', $content->id)
            ->delete();
    }

    public function rebuild(): int
    {
        SearchDocument::query()->delete();
        $count = 0;

        Content::query()->where('status', 'published')->chunkById(100, function ($contents) use (&$count): void {
            foreach ($contents as $content) {
                $this->index($content);
                $count++;
            }
        });

        return $count;
    }
}

