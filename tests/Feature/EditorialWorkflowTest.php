<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EditorialWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_update_creates_revision_and_can_restore_it(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
        $content = Content::query()->where('slug', 'premier-article')->firstOrFail();

        $this->actingAs($admin)->put("/admin/pages/{$content->id}", [
            'type' => 'post',
            'status' => 'published',
            'title' => 'Article modifie',
            'slug' => 'premier-article',
            'excerpt' => 'Extrait modifie',
            'locale' => 'fr',
            'body_markdown' => 'Nouveau corps',
        ])->assertRedirect();

        $revision = $content->revisions()->firstOrFail();

        $this->actingAs($admin)
            ->post("/admin/pages/{$content->id}/revisions/{$revision->id}/restore")
            ->assertRedirect();

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'title' => 'Premier article',
        ]);
    }

    public function test_scheduled_content_command_publishes_due_content(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
        $content = Content::query()->create([
            'type' => 'post',
            'status' => 'scheduled',
            'author_id' => $admin->id,
            'slug' => 'programme',
            'title' => 'Programme',
            'locale' => 'fr',
            'body_json' => ['version' => 1, 'blocks' => []],
            'scheduled_at' => now()->subMinute(),
        ]);

        $this->artisan('librepress:publish-scheduled')->assertSuccessful();

        $this->assertSame('published', $content->refresh()->status);
    }

    public function test_admin_can_create_menu_item(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)->post('/admin/menus', [
            'name' => 'Footer',
            'slug' => 'footer',
            'location' => 'footer',
        ])->assertRedirect();

        $menu = Menu::query()->where('slug', 'footer')->firstOrFail();

        $this->actingAs($admin)->post("/admin/menus/{$menu->id}/items", [
            'label' => 'Contact',
            'url' => '/contact',
            'sort_order' => 10,
        ])->assertRedirect();

        $this->assertDatabaseHas('menu_items', ['menu_id' => $menu->id, 'url' => '/contact']);
    }
}

