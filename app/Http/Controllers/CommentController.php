<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Content;
use App\Services\CommentModeration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final readonly class CommentController
{
    public function __construct(private CommentModeration $moderation)
    {
    }

    public function store(Request $request, Content $content): RedirectResponse
    {
        abort_unless($content->status === 'published', 404);

        $validated = $request->validate([
            'author_name' => ['required_without:user_id', 'string', 'max:120'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string', 'min:3', 'max:4000'],
            'website' => ['nullable', 'string', 'max:255'],
        ]);
        $moderation = $this->moderation->inspect($validated['body'], $validated['website'] ?? null);

        Comment::query()->create([
            'content_id' => $content->id,
            'user_id' => $request->user()?->id,
            'author_name' => $request->user()?->name ?? $validated['author_name'],
            'author_email_hash' => isset($validated['author_email']) ? hash('sha256', strtolower($validated['author_email'])) : null,
            'body' => $validated['body'],
            'status' => $moderation['status'],
            'is_spam' => $moderation['is_spam'],
            'moderation_reason' => $moderation['reason'],
            'ip_hash' => hash('sha256', (string) $request->ip()),
            'user_agent_hash' => hash('sha256', (string) $request->userAgent()),
        ]);

        return back()->with('status', $moderation['status'] === 'approved' ? 'Commentaire publie.' : 'Commentaire envoye en moderation.');
    }

    public function report(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'in:spam,abuse,privacy,other'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        CommentReport::query()->create([
            'comment_id' => $comment->id,
            'reason' => $validated['reason'],
            'message' => $validated['message'] ?? null,
            'ip_hash' => hash('sha256', (string) $request->ip()),
        ]);

        $comment->increment('reports_count');

        return back()->with('status', 'Signalement envoye.');
    }
}
