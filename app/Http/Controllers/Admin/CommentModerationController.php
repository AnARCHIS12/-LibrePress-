<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class CommentModerationController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', Comment::class);

        return view('admin.comments.index', [
            'comments' => Comment::query()->with('content')->latest()->paginate(30),
        ]);
    }

    public function approve(Request $request, Comment $comment): RedirectResponse
    {
        Gate::authorize('update', $comment);

        $comment->update(['status' => 'approved']);
        $this->audit->log($request, 'comment.approved', $comment);

        return back()->with('status', 'Commentaire approuve.');
    }

    public function reject(Request $request, Comment $comment): RedirectResponse
    {
        Gate::authorize('update', $comment);

        $comment->update(['status' => 'rejected']);
        $this->audit->log($request, 'comment.rejected', $comment);

        return back()->with('status', 'Commentaire rejete.');
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $this->audit->log($request, 'comment.deleted', $comment);
        $comment->delete();

        return back()->with('status', 'Commentaire supprime.');
    }
}
