<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Redirect;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class RedirectController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', Redirect::class);

        return view('admin.redirects.index', [
            'redirects' => Redirect::query()->latest()->paginate(30),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Redirect::class);

        $validated = $request->validate([
            'source_path' => ['required', 'string', 'max:255', 'starts_with:/', 'unique:redirects,source_path'],
            'target_path' => ['required', 'string', 'max:255'],
            'status_code' => ['required', 'integer', 'in:301,302,307,308'],
        ]);

        $redirect = Redirect::query()->create($validated);
        $this->audit->log($request, 'redirect.created', $redirect);

        return back()->with('status', 'Redirection creee.');
    }

    public function destroy(Request $request, Redirect $redirect): RedirectResponse
    {
        Gate::authorize('delete', $redirect);

        $this->audit->log($request, 'redirect.deleted', $redirect);
        $redirect->delete();

        return back()->with('status', 'Redirection supprimee.');
    }
}

