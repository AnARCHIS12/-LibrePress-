<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CommentController
{
    public function store(Request $request, Content $content): RedirectResponse
    {
        abort_unless($content->status === 'published', 404);

        $validated = $request->validate([
            'author_name' => ['required_without:user_id', 'string', 'max:120'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string', 'min:3', 'max:4000'],
        ]);

        Comment::query()->create([
            'content_id' => $content->id,
            'user_id' => $request->user()?->id,
            'author_name' => $request->user()?->name ?? $validated['author_name'],
            'author_email_hash' => isset($validated['author_email']) ? hash('sha256', strtolower($validated['author_email'])) : null,
            'body' => $validated['body'],
            'status' => 'approved',
            'ip_hash' => hash('sha256', (string) $request->ip()),
            'user_agent_hash' => hash('sha256', (string) $request->userAgent()),
        ]);

        return back()->with('status', 'Commentaire publie.');
    }
}

