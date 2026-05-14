<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Taxonomy;
use App\Models\Term;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final readonly class TermController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function store(Request $request, Taxonomy $taxonomy): RedirectResponse
    {
        Gate::authorize('update', $taxonomy);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120'],
        ]);

        $term = Term::query()->create([
            'taxonomy_id' => $taxonomy->id,
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
        ]);
        $this->audit->log($request, 'term.created', $term, ['taxonomy' => $taxonomy->slug]);

        return back()->with('status', 'Terme cree.');
    }
}

