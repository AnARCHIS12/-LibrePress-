<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthAndAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_super_admin_can_create_content_and_audit_is_written(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)->post('/admin/pages', [
            'type' => 'page',
            'status' => 'published',
            'title' => 'Page test',
            'slug' => 'page-test',
            'excerpt' => 'Une page de test.',
            'locale' => 'fr',
            'body_markdown' => '# Test',
        ])->assertRedirect();

        $this->assertDatabaseHas('contents', ['slug' => 'page-test', 'type' => 'page']);
        $this->assertDatabaseHas('search_documents', ['title' => 'Page test']);
        $this->assertDatabaseHas('activity_log', ['description' => 'content.created']);
    }

    public function test_user_without_admin_permission_is_forbidden(): void
    {
        $this->seed();
        $user = User::query()->create([
            'name' => 'Visitor',
            'email' => 'visitor@example.test',
            'password' => 'password',
        ]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }
}
