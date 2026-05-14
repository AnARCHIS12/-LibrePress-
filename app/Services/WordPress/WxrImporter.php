<?php

declare(strict_types=1);

namespace App\Services\WordPress;

use App\Models\Content;
use App\Models\ImportRun;
use App\Models\Media;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Redirect;
use App\Models\Taxonomy;
use App\Models\Term;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleXMLElement;

final class WxrImporter
{
    public function __construct(private \App\Services\SearchIndexer $search)
    {
    }

    /**
     * @return array<string, int>
     */
    public function import(string $path, User $fallbackAuthor): array
    {
        $run = ImportRun::query()->create([
            'source' => 'wordpress-wxr',
            'file_path' => $path,
            'created_by' => $fallbackAuthor->id,
            'status' => 'running',
            'summary' => [],
        ]);
        $summary = ['authors' => 0, 'contents' => 0, 'attachments' => 0, 'menus' => 0, 'terms' => 0, 'redirects' => 0];
        $xml = new SimpleXMLElement(file_get_contents($path) ?: '');
        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
        $excerptNamespace = $namespaces['excerpt'] ?? 'http://wordpress.org/export/1.2/excerpt/';
        $contentNamespace = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
        $dcNamespace = $namespaces['dc'] ?? 'http://purl.org/dc/elements/1.1/';
        $authors = $this->importAuthors($xml, $wp, $fallbackAuthor, $summary);
        $termsByWpId = $this->importTerms($xml, $wp, $summary);
        $contentsByWpId = [];

        foreach ($xml->channel->item as $item) {
            $wpNode = $item->children($wp);
            $contentNode = $item->children($contentNamespace);
            $excerptNode = $item->children($excerptNamespace);
            $postType = (string) $wpNode->post_type;
            $status = (string) $wpNode->status;
            $wpId = (string) $wpNode->post_id;

            if ($postType === 'attachment') {
                $this->importAttachment($item, $wpNode, $fallbackAuthor, $summary);
                continue;
            }

            if ($postType === 'nav_menu_item') {
                continue;
            }

            if (! in_array($postType, ['page', 'post'], true)) {
                continue;
            }

            $title = trim((string) $item->title) ?: 'Sans titre';
            $slug = trim((string) $wpNode->post_name) ?: Str::slug($title);
            $html = (string) $contentNode->encoded;
            $authorLogin = (string) $item->children($dcNamespace)->creator;
            $author = $authors[$authorLogin] ?? $fallbackAuthor;
            $oldPath = parse_url((string) $item->link, PHP_URL_PATH) ?: null;

            $content = Content::query()->updateOrCreate(
                ['type' => $postType, 'locale' => 'fr', 'slug' => $slug],
                [
                    'status' => $status === 'publish' ? 'published' : 'draft',
                    'author_id' => $author->id,
                    'title' => $title,
                    'excerpt' => trim((string) $excerptNode->encoded) ?: trim((string) $item->description) ?: null,
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
                    'meta' => ['imported_from' => 'wordpress-wxr', 'wordpress_id' => $wpId],
                ],
            );

            $this->syncItemTerms($item, $termsByWpId, $content);
            $this->search->index($content);
            $contentsByWpId[$wpId] = $content;
            $summary['contents']++;

            if ($oldPath && $oldPath !== '/'.$slug) {
                Redirect::query()->updateOrCreate(
                    ['source_path' => $oldPath],
                    ['target_path' => route('front.show', $slug, false), 'status_code' => 301],
                );
                $summary['redirects']++;
            }
        }

        $summary['menus'] = $this->importMenus($xml, $wp, $contentsByWpId);
        $run->update(['status' => 'finished', 'summary' => $summary]);

        return $summary;
    }

    /**
     * @param array<string, int> $summary
     * @return array<string, User>
     */
    private function importAuthors(SimpleXMLElement $xml, string $wp, User $fallbackAuthor, array &$summary): array
    {
        $authors = [];

        foreach ($xml->channel->children($wp)->author as $authorNode) {
            $login = (string) $authorNode->author_login;
            $email = (string) $authorNode->author_email ?: $login.'@import.local';

            $authors[$login] = User::query()->firstOrCreate(
                ['email' => $email],
                [
                    'name' => (string) $authorNode->author_display_name ?: $login,
                    'password' => Hash::make(Str::random(32)),
                    'status' => 'active',
                    'locale' => $fallbackAuthor->locale,
                ],
            );
            $summary['authors']++;
        }

        return $authors;
    }

    /**
     * @param array<string, int> $summary
     * @return array<string, Term>
     */
    private function importTerms(SimpleXMLElement $xml, string $wp, array &$summary): array
    {
        $terms = [];

        foreach (['category' => 'categories', 'tag' => 'tags', 'term' => 'collections'] as $nodeName => $taxonomySlug) {
            foreach ($xml->channel->children($wp)->{$nodeName} as $node) {
                $slug = (string) ($node->category_nicename ?? $node->tag_slug ?? $node->term_slug);
                $name = (string) ($node->cat_name ?? $node->tag_name ?? $node->term_name);
                $wpId = (string) ($node->term_id ?? $slug);

                if ($slug === '' || $name === '') {
                    continue;
                }

                $taxonomy = Taxonomy::query()->firstOrCreate(
                    ['slug' => $taxonomySlug],
                    ['name' => Str::headline($taxonomySlug), 'owner' => 'wordpress'],
                );
                $term = Term::query()->firstOrCreate(
                    ['taxonomy_id' => $taxonomy->id, 'slug' => $slug],
                    ['name' => $name, 'meta' => ['wordpress_id' => $wpId]],
                );
                $terms[$slug] = $term;
                $terms[$wpId] = $term;
                $summary['terms']++;
            }
        }

        return $terms;
    }

    /**
     * @param array<string, Term> $termsByWpId
     */
    private function syncItemTerms(SimpleXMLElement $item, array $termsByWpId, Content $content): void
    {
        $termIds = [];

        foreach ($item->category as $category) {
            $slug = (string) $category['nicename'];

            if (isset($termsByWpId[$slug])) {
                $termIds[] = $termsByWpId[$slug]->id;
            }
        }

        $content->terms()->sync(array_unique($termIds));
    }

    /**
     * @param array<string, int> $summary
     */
    private function importAttachment(SimpleXMLElement $item, SimpleXMLElement $wpNode, User $author, array &$summary): void
    {
        $url = (string) $wpNode->attachment_url;

        if ($url === '') {
            return;
        }

        Media::query()->updateOrCreate(
            ['path' => $url],
            [
                'disk' => 'remote',
                'mime_type' => 'application/octet-stream',
                'size' => 0,
                'alt' => (string) $item->title ?: null,
                'hash' => hash('sha256', $url),
                'created_by' => $author->id,
                'caption' => 'Imported WordPress attachment',
            ],
        );
        $summary['attachments']++;
    }

    /**
     * @param array<string, Content> $contentsByWpId
     */
    private function importMenus(SimpleXMLElement $xml, string $wp, array $contentsByWpId): int
    {
        $count = 0;
        $menu = Menu::query()->firstOrCreate(
            ['slug' => 'wordpress-import'],
            ['name' => 'WordPress Import', 'location' => 'primary'],
        );

        foreach ($xml->channel->item as $item) {
            $wpNode = $item->children($wp);

            if ((string) $wpNode->post_type !== 'nav_menu_item') {
                continue;
            }

            $meta = $this->meta($wpNode);
            $objectId = (string) ($meta['_menu_item_object_id'] ?? '');
            $url = (string) ($meta['_menu_item_url'] ?? '');
            $target = $contentsByWpId[$objectId] ?? null;

            MenuItem::query()->create([
                'menu_id' => $menu->id,
                'label' => (string) $item->title ?: 'Menu item',
                'url' => $target ? route('front.show', $target->slug, false) : ($url ?: '/'),
                'sort_order' => $count * 10,
                'new_tab' => false,
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * @return array<string, string>
     */
    private function meta(SimpleXMLElement $wpNode): array
    {
        $meta = [];

        foreach ($wpNode->postmeta as $postmeta) {
            $meta[(string) $postmeta->meta_key] = (string) $postmeta->meta_value;
        }

        return $meta;
    }
}
