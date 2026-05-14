<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Module;
use App\Services\AuditLogger;
use App\Services\ExtensionDiscovery;
use App\Services\ExtensionSecurity;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class ModuleController
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
        Gate::authorize('viewAny', Module::class);

        $installed = Module::query()->get()->keyBy('slug');

        return view('admin.modules.index', [
            'modules' => collect($this->discovery->modules())->map(fn (array $manifest): array => [
                ...$manifest,
                'compatible' => $this->security->isCompatible($manifest),
                'checksum' => $this->security->checksum($manifest),
                'record' => $installed->get($manifest['slug']),
            ]),
        ]);
    }

    public function enable(Request $request, string $slug): RedirectResponse
    {
        Gate::authorize('viewAny', Module::class);

        $manifest = collect($this->discovery->modules())->firstWhere('slug', $slug);
        abort_unless($manifest, 404);
        abort_unless($this->security->isCompatible($manifest), 422, 'Module incompatible avec cette version du coeur.');

        $module = Module::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $manifest['name'],
                'version' => $manifest['version'],
                'enabled' => true,
                'manifest' => [...$manifest, 'checksum' => $this->security->checksum($manifest)],
                'installed_at' => now(),
                'enabled_at' => now(),
            ],
        );
        $this->audit->log($request, 'module.enabled', $module, ['slug' => $slug]);

        return back()->with('status', "Module $slug active.");
    }

    public function disable(Request $request, string $slug): RedirectResponse
    {
        Gate::authorize('viewAny', Module::class);

        $module = Module::query()->where('slug', $slug)->firstOrFail();
        $module->update([
            'enabled' => false,
            'enabled_at' => null,
        ]);
        $this->audit->log($request, 'module.disabled', $module, ['slug' => $slug]);

        return back()->with('status', "Module $slug desactive.");
    }

    public function uninstall(Request $request, string $slug): RedirectResponse
    {
        Gate::authorize('viewAny', Module::class);

        $module = Module::query()->where('slug', $slug)->firstOrFail();
        abort_if($module->enabled, 422, 'Desactive le module avant de le desinstaller.');

        $this->audit->log($request, 'module.uninstalled', $module, ['slug' => $slug]);
        $module->delete();

        return back()->with('status', "Module $slug desinstalle.");
    }
}
