<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Theme;
use App\Services\AuditLogger;
use App\Services\ExtensionDiscovery;
use App\Services\ExtensionSecurity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class ThemeController
{
    public function __construct(
        private ExtensionDiscovery $discovery,
        private ExtensionSecurity $security,
        private AuditLogger $audit,
    )
    {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', Theme::class);

        $installed = Theme::query()->get()->keyBy('slug');

        return view('admin.themes.index', [
            'themes' => collect($this->discovery->themes())->map(fn (array $manifest): array => [
                ...$manifest,
                'compatible' => $this->security->isCompatible($manifest),
                'checksum' => $this->security->checksum($manifest),
                'record' => $installed->get($manifest['slug']),
            ]),
        ]);
    }

    public function activate(Request $request, string $slug): RedirectResponse
    {
        Gate::authorize('viewAny', Theme::class);

        $manifest = collect($this->discovery->themes())->firstWhere('slug', $slug);
        abort_unless($manifest, 404);
        abort_unless($this->security->isCompatible($manifest), 422, 'Theme incompatible avec cette version du coeur.');

        $theme = DB::transaction(function () use ($slug, $manifest): Theme {
            Theme::query()->update(['enabled' => false]);

            return Theme::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $manifest['name'],
                    'version' => $manifest['version'],
                    'enabled' => true,
                    'config' => [...$manifest, 'checksum' => $this->security->checksum($manifest)],
                ],
            );
        });
        $this->audit->log($request, 'theme.activated', $theme, ['slug' => $slug]);

        return back()->with('status', "Theme $slug active.");
    }

    public function preview(string $slug): View
    {
        Gate::authorize('viewAny', Theme::class);

        $manifest = collect($this->discovery->themes())->firstWhere('slug', $slug);
        abort_unless($manifest, 404);

        return view('admin.themes.preview', [
            'theme' => $manifest,
            'compatible' => $this->security->isCompatible($manifest),
            'checksum' => $this->security->checksum($manifest),
        ]);
    }
}
