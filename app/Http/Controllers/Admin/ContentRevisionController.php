<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Models\ContentRevision;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class ContentRevisionController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function index(Content $content): View
    {
        Gate::authorize('update', $content);

        return view('admin.revisions.index', [
            'content' => $content,
            'revisions' => $content->revisions()->with('user')->latest()->paginate(20),
        ]);
    }

    public function restore(Request $request, Content $content, ContentRevision $revision): RedirectResponse
    {
        Gate::authorize('update', $content);
        abort_unless($revision->content_id === $content->id, 404);

        $content->update([
            'title' => $revision->title,
            'body_json' => $revision->body_json,
            'body_html' => $revision->body_html,
            'meta' => $revision->meta,
        ]);

        $this->audit->log($request, 'content.revision_restored', $content, ['revision_id' => $revision->id]);

        return redirect()->route('admin.pages.edit', $content)->with('status', 'Revision restauree.');
    }
}

