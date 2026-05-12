<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Module;
use App\Services\ExtensionDiscovery;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final readonly class ModuleController
{
    public function __construct(private ExtensionDiscovery $discovery)
    {
    }

    public function index(): View
    {
        $installed = Module::query()->get()->keyBy('slug');

        return view('admin.modules.index', [
            'modules' => collect($this->discovery->modules())->map(fn (array $manifest): array => [
                ...$manifest,
                'record' => $installed->get($manifest['slug']),
            ]),
        ]);
    }

    public function enable(string $slug): RedirectResponse
    {
        $manifest = collect($this->discovery->modules())->firstWhere('slug', $slug);
        abort_unless($manifest, 404);

        Module::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $manifest['name'],
                'version' => $manifest['version'],
                'enabled' => true,
                'manifest' => $manifest,
                'installed_at' => now(),
                'enabled_at' => now(),
            ],
        );

        return back()->with('status', "Module $slug active.");
    }

    public function disable(string $slug): RedirectResponse
    {
        Module::query()->where('slug', $slug)->update([
            'enabled' => false,
            'enabled_at' => null,
        ]);

        return back()->with('status', "Module $slug desactive.");
    }
}

