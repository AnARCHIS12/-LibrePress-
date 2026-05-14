<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContentRequest;
use App\Models\Content;
use App\Models\Term;
use App\Services\AuditLogger;
use App\Services\BlockRenderer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class PageController
{
    public function __construct(
        private BlockRenderer $renderer,
        private AuditLogger $audit,
    )
    {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', Content::class);

        return view('admin.pages.index', [
            'contents' => Content::query()->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Content::class);

        return view('admin.pages.form', [
            'content' => new Content(['type' => 'page', 'status' => 'draft', 'locale' => 'fr']),
            'terms' => Term::query()->with('taxonomy')->orderBy('name')->get(),
            'selectedTerms' => [],
        ]);
    }

    public function store(ContentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Content::class);

        $content = Content::query()->create($request->toContentData());
        $content->update(['body_html' => $this->renderer->render($content)]);
        $content->terms()->sync($request->validated('terms') ?? []);
        $this->audit->log($request, 'content.created', $content, ['type' => $content->type]);

        return redirect()->route('admin.pages.edit', $content)->with('status', 'Contenu cree.');
    }

    public function edit(Content $page): View
    {
        Gate::authorize('update', $page);

        return view('admin.pages.form', [
            'content' => $page->load('terms'),
            'terms' => Term::query()->with('taxonomy')->orderBy('name')->get(),
            'selectedTerms' => $page->terms->pluck('id')->all(),
        ]);
    }

    public function update(ContentRequest $request, Content $page): RedirectResponse
    {
        Gate::authorize('update', $page);

        $page->revisions()->create([
            'user_id' => $request->user()?->id,
            'title' => $page->title,
            'body_json' => $page->body_json,
            'body_html' => $page->body_html,
            'meta' => $page->meta,
        ]);

        $page->fill($request->toContentData());
        $page->body_html = $this->renderer->render($page);
        $page->save();
        $page->terms()->sync($request->validated('terms') ?? []);
        $this->audit->log($request, 'content.updated', $page, ['type' => $page->type]);

        return back()->with('status', 'Contenu enregistre.');
    }

    public function destroy(Request $request, Content $page): RedirectResponse
    {
        Gate::authorize('delete', $page);

        $this->audit->log($request, 'content.deleted', $page, ['type' => $page->type]);
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Contenu supprime.');
    }
}
