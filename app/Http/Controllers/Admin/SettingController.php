<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\SettingStore;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class SettingController
{
    public function __construct(
        private SettingStore $settings,
        private AuditLogger $audit,
    )
    {
    }

    public function edit(): View
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);

        return view('admin.settings.edit', [
            'siteName' => $this->settings->get('site.name', config('app.name')),
            'siteDescription' => $this->settings->get('site.description', 'CMS Laravel libre et modulaire.'),
            'activitypubEnabled' => (bool) $this->settings->get('activitypub.enabled', false),
            'commentsEnabled' => (bool) $this->settings->get('comments.enabled', true),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);

        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_description' => ['nullable', 'string', 'max:255'],
            'activitypub_enabled' => ['nullable', 'boolean'],
            'comments_enabled' => ['nullable', 'boolean'],
        ]);

        $this->settings->set('site.name', $validated['site_name']);
        $this->settings->set('site.description', $validated['site_description'] ?? '');
        $this->settings->set('activitypub.enabled', $request->boolean('activitypub_enabled'));
        $this->settings->set('comments.enabled', $request->boolean('comments_enabled'));
        $this->audit->log($request, 'settings.updated');

        return back()->with('status', 'Reglages enregistres.');
    }
}
