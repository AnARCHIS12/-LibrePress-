<?php

declare(strict_types=1);

namespace App\Services\WordPress;

use App\Models\Content;
use App\Models\User;
use Illuminate\Support\Str;
use SimpleXMLElement;

final class WxrImporter
{
    public function import(string $path, User $author): int
    {
        $xml = new SimpleXMLElement(file_get_contents($path) ?: '');
        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
        $contentNamespace = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
        $count = 0;

        foreach ($xml->channel->item as $item) {
            $wpNode = $item->children($wp);
            $contentNode = $item->children($contentNamespace);
            $postType = (string) $wpNode->post_type;
            $status = (string) $wpNode->status;

            if (! in_array($postType, ['page', 'post'], true)) {
                continue;
            }

            $title = trim((string) $item->title) ?: 'Sans titre';
            $slug = trim((string) $wpNode->post_name) ?: Str::slug($title);
            $html = (string) $contentNode->encoded;

            Content::query()->updateOrCreate(
                ['type' => $postType, 'locale' => 'fr', 'slug' => $slug],
                [
                    'status' => $status === 'publish' ? 'published' : 'draft',
                    'author_id' => $author->id,
                    'title' => $title,
                    'excerpt' => trim((string) $item->description) ?: null,
                    'body_json' => [
                        'version' => 1,
                        'blocks' => [[
                            'id' => (string) Str::uuid(),
                            'type' => 'core/markdown',
                            'props' => ['text' => strip_tags($html, '<p><h1><h2><h3><ul><ol><li><a><strong><em><blockquote><code><pre><img>')],
                        ]],
                    ],
                    'body_html' => $html,
                    'published_at' => $status === 'publish' ? now() : null,
                    'meta' => ['imported_from' => 'wordpress-wxr'],
                ],
            );

            $count++;
        }

        return $count;
    }
}

