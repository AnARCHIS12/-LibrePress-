<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Content;
use App\Models\ActivityPubActor;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Module;
use App\Models\Setting;
use App\Models\Taxonomy;
use App\Models\Term;
use App\Models\Theme;
use App\Models\User;
use App\Support\CmsPermission;
use App\Services\SearchIndexer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'locale' => 'fr',
            ],
        );

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (CmsPermission::all() as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::query()->firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $editor = Role::query()->firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $author = Role::query()->firstOrCreate(['name' => 'author', 'guard_name' => 'web']);
        $moderator = Role::query()->firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);

        $superAdmin->syncPermissions(CmsPermission::all());
        $editor->syncPermissions([
            CmsPermission::ADMIN_ACCESS,
            CmsPermission::CONTENT_VIEW,
            CmsPermission::CONTENT_CREATE,
            CmsPermission::CONTENT_UPDATE,
            CmsPermission::CONTENT_PUBLISH,
            CmsPermission::MEDIA_VIEW,
            CmsPermission::MEDIA_UPLOAD,
        ]);
        $author->syncPermissions([
            CmsPermission::ADMIN_ACCESS,
            CmsPermission::CONTENT_VIEW,
            CmsPermission::CONTENT_CREATE,
            CmsPermission::CONTENT_UPDATE,
            CmsPermission::MEDIA_VIEW,
        ]);
        $moderator->syncPermissions([
            CmsPermission::ADMIN_ACCESS,
            CmsPermission::COMMENTS_MODERATE,
        ]);

        $admin->assignRole($superAdmin);

        ActivityPubActor::query()->firstOrCreate(
            ['username' => 'admin'],
            [
                'user_id' => $admin->id,
                'type' => 'Person',
                'public_key' => null,
                'private_key' => null,
                'enabled' => true,
            ],
        );

        Content::query()->firstOrCreate(
            ['type' => 'page', 'locale' => 'fr', 'slug' => 'accueil'],
            [
                'status' => 'published',
                'author_id' => $admin->id,
                'title' => 'LibrePress',
                'excerpt' => 'Un CMS Laravel libre, modulaire et auto-hebergeable.',
                'body_json' => [
                    'version' => 1,
                    'blocks' => [
                        [
                            'id' => 'intro',
                            'type' => 'core/markdown',
                            'props' => [
                                'text' => "## Bienvenue\n\nLibrePress est pret: pages, articles, admin, medias, RSS, API et architecture de modules.",
                            ],
                        ],
                    ],
                ],
                'published_at' => now(),
            ],
        );

        Content::query()->firstOrCreate(
            ['type' => 'post', 'locale' => 'fr', 'slug' => 'premier-article'],
            [
                'status' => 'published',
                'author_id' => $admin->id,
                'title' => 'Premier article',
                'excerpt' => 'Le module Blog officiel peut etendre ce socle.',
                'body_json' => [
                    'version' => 1,
                    'blocks' => [
                        [
                            'id' => 'post-body',
                            'type' => 'core/markdown',
                            'props' => ['text' => "Ceci est un article publie depuis le seed initial."],
                        ],
                    ],
                ],
                'published_at' => now(),
            ],
        );

        $category = Taxonomy::query()->firstOrCreate(
            ['slug' => 'categories'],
            ['name' => 'Categories', 'owner' => 'core'],
        );

        $tag = Taxonomy::query()->firstOrCreate(
            ['slug' => 'tags'],
            ['name' => 'Tags', 'owner' => 'core'],
        );

        Term::query()->firstOrCreate(
            ['taxonomy_id' => $category->id, 'slug' => 'actualites'],
            ['name' => 'Actualites'],
        );

        Term::query()->firstOrCreate(
            ['taxonomy_id' => $tag->id, 'slug' => 'cms'],
            ['name' => 'CMS'],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'site.name'],
            ['value' => ['value' => 'LibrePress'], 'type' => 'string', 'scope' => 'site', 'autoload' => true],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'site.description'],
            ['value' => ['value' => 'Un CMS Laravel libre, modulaire et auto-hebergeable.'], 'type' => 'string', 'scope' => 'site', 'autoload' => true],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'comments.enabled'],
            ['value' => ['value' => true], 'type' => 'bool', 'scope' => 'site', 'autoload' => true],
        );

        Theme::query()->updateOrCreate(
            ['slug' => 'nova'],
            ['name' => 'Nova', 'version' => '0.1.0', 'enabled' => true, 'config' => ['source' => 'seed']],
        );

        Module::query()->updateOrCreate(
            ['slug' => 'blog'],
            ['name' => 'Blog', 'version' => '0.1.0', 'enabled' => true, 'manifest' => ['source' => 'seed'], 'installed_at' => now(), 'enabled_at' => now()],
        );

        $menu = Menu::query()->firstOrCreate(
            ['slug' => 'principal'],
            ['name' => 'Principal', 'location' => 'primary'],
        );

        foreach ([
            ['label' => 'Blog', 'url' => '/blog', 'sort_order' => 10],
            ['label' => 'Recherche', 'url' => '/search', 'sort_order' => 20],
            ['label' => 'RSS', 'url' => '/feed.xml', 'sort_order' => 30],
        ] as $item) {
            MenuItem::query()->firstOrCreate(
                ['menu_id' => $menu->id, 'url' => $item['url']],
                $item,
            );
        }

        app(SearchIndexer::class)->rebuild();
    }
}
