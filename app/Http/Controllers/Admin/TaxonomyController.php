<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Taxonomy;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

final readonly class TaxonomyController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', Taxonomy::class);

        return view('admin.taxonomies.index', [
            'taxonomies' => Taxonomy::query()->withCount('terms')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Taxonomy::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:taxonomies,slug'],
        ]);

        $taxonomy = Taxonomy::query()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'owner' => 'core',
        ]);
        $this->audit->log($request, 'taxonomy.created', $taxonomy);

        return back()->with('status', 'Taxonomie creee.');
    }
}

