<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class BackupController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);

        return view('admin.backups.index');
    }

    public function backup(Request $request): RedirectResponse
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);

        Artisan::call('librepress:backup');
        $this->audit->log($request, 'backup.created');

        return back()->with('status', 'Sauvegarde creee.');
    }

    public function export(Request $request): RedirectResponse
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);

        Artisan::call('librepress:export');
        $this->audit->log($request, 'export.created');

        return back()->with('status', 'Export cree.');
    }
}

