<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class CommentModerationController
{
    public function index(): View
    {
        return view('admin.comments.index', [
            'comments' => Comment::query()->with('content')->latest()->paginate(30),
        ]);
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'approved']);

        return back()->with('status', 'Commentaire approuve.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('status', 'Commentaire rejete.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Commentaire supprime.');
    }
}

