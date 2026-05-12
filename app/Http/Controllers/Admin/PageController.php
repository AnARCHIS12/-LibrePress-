<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContentRequest;
use App\Models\Content;
use App\Services\BlockRenderer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final readonly class PageController
{
    public function __construct(private BlockRenderer $renderer)
    {
    }

    public function index(): View
    {
        return view('admin.pages.index', [
            'contents' => Content::query()->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.form', [
            'content' => new Content(['type' => 'page', 'status' => 'draft', 'locale' => 'fr']),
        ]);
    }

    public function store(ContentRequest $request): RedirectResponse
    {
        $content = Content::query()->create($request->toContentData());
        $content->update(['body_html' => $this->renderer->render($content)]);

        return redirect()->route('admin.pages.edit', $content)->with('status', 'Contenu cree.');
    }

    public function edit(Content $page): View
    {
        return view('admin.pages.form', ['content' => $page]);
    }

    public function update(ContentRequest $request, Content $page): RedirectResponse
    {
        $page->fill($request->toContentData());
        $page->body_html = $this->renderer->render($page);
        $page->save();

        return back()->with('status', 'Contenu enregistre.');
    }

    public function destroy(Content $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Contenu supprime.');
    }
}

