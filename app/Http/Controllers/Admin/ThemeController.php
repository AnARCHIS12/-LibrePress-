<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Theme;
use App\Services\ExtensionDiscovery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final readonly class ThemeController
{
    public function __construct(private ExtensionDiscovery $discovery)
    {
    }

    public function index(): View
    {
        $installed = Theme::query()->get()->keyBy('slug');

        return view('admin.themes.index', [
            'themes' => collect($this->discovery->themes())->map(fn (array $manifest): array => [
                ...$manifest,
                'record' => $installed->get($manifest['slug']),
            ]),
        ]);
    }

    public function activate(string $slug): RedirectResponse
    {
        $manifest = collect($this->discovery->themes())->firstWhere('slug', $slug);
        abort_unless($manifest, 404);

        DB::transaction(function () use ($slug, $manifest): void {
            Theme::query()->update(['enabled' => false]);
            Theme::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $manifest['name'],
                    'version' => $manifest['version'],
                    'enabled' => true,
                    'config' => $manifest,
                ],
            );
        });

        return back()->with('status', "Theme $slug active.");
    }
}

