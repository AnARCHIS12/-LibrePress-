<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommentAndPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_comment_can_be_posted(): void
    {
        $this->seed();
        $content = Content::query()->where('slug', 'premier-article')->firstOrFail();

        $this->post("/contents/{$content->id}/comments", [
            'author_name' => 'Lecteur',
            'author_email' => 'lecteur@example.test',
            'body' => 'Tres bon article.',
        ])->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'content_id' => $content->id,
            'author_name' => 'Lecteur',
            'status' => 'approved',
        ]);
    }

    public function test_honeypot_comment_is_sent_to_moderation_and_can_be_reported(): void
    {
        $this->seed();
        $content = Content::query()->where('slug', 'premier-article')->firstOrFail();

        $this->post("/contents/{$content->id}/comments", [
            'author_name' => 'Robot',
            'body' => 'Message automatisé',
            'website' => 'https://spam.example',
        ])->assertRedirect();

        $comment = Comment::query()->where('author_name', 'Robot')->firstOrFail();
        $this->assertTrue($comment->is_spam);
        $this->assertSame('rejected', $comment->status);

        $this->post("/comments/{$comment->id}/report", [
            'reason' => 'spam',
            'message' => 'Spam visible',
        ])->assertRedirect();

        $this->assertSame(1, $comment->refresh()->reports_count);
    }

    public function test_moderation_requires_permission(): void
    {
        $this->seed();
        $content = Content::query()->where('slug', 'premier-article')->firstOrFail();
        $comment = Comment::query()->create([
            'content_id' => $content->id,
            'author_name' => 'Lecteur',
            'body' => 'A moderer',
            'status' => 'pending',
        ]);
        $user = User::query()->create([
            'name' => 'Editorless',
            'email' => 'editorless@example.test',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $this->actingAs($user)
            ->patch("/admin/comments/{$comment->id}/approve")
            ->assertForbidden();
    }
}
