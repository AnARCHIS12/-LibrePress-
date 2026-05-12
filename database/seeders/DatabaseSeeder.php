<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Module;
use App\Models\Setting;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
    }
}
