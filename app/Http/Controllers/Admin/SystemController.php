<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\AuditLogger;
use App\Services\PublicCache;
use App\Services\SystemHealth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class SystemController
{
    public function __construct(
        private SystemHealth $health,
        private PublicCache $cache,
        private AuditLogger $audit,
    ) {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);

        return view('admin.system.index', [
            'checks' => $this->health->checks(),
        ]);
    }

    public function clearCache(Request $request): RedirectResponse
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);

        $this->cache->flush();
        $this->audit->log($request, 'cache.flushed');

        return back()->with('status', 'Cache vide.');
    }
}

