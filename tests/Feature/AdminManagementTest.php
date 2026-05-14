<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_manage_taxonomies(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)->post('/admin/taxonomies', [
            'name' => 'Collections',
            'slug' => 'collections',
        ])->assertRedirect();

        $taxonomy = Taxonomy::query()->where('slug', 'collections')->firstOrFail();

        $this->actingAs($admin)->post("/admin/taxonomies/{$taxonomy->id}/terms", [
            'name' => 'Guides',
            'slug' => 'guides',
        ])->assertRedirect();

        $this->assertDatabaseHas('terms', [
            'taxonomy_id' => $taxonomy->id,
            'slug' => 'guides',
        ]);
    }

    public function test_super_admin_can_assign_roles_to_another_user(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
        $user = User::query()->create([
            'name' => 'Writer',
            'email' => 'writer@example.test',
            'password' => 'password',
        ]);

        $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'Writer',
            'status' => 'active',
            'roles' => ['author'],
        ])->assertRedirect();

        $this->assertTrue($user->refresh()->hasRole('author'));
    }

    public function test_super_admin_can_create_translation(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
        $content = \App\Models\Content::query()->where('slug', 'premier-article')->firstOrFail();

        $this->actingAs($admin)->post("/admin/pages/{$content->id}/translations", [
            'locale' => 'en',
        ])->assertRedirect();

        $this->assertDatabaseHas('contents', [
            'locale' => 'en',
            'slug' => 'premier-article-en',
            'status' => 'draft',
        ]);
    }

    public function test_super_admin_can_preview_theme_and_uninstall_disabled_module(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)->get('/admin/themes/nova/preview')->assertOk()->assertSee('Nova');
        $this->actingAs($admin)->post('/admin/modules/blog/disable')->assertRedirect();
        $this->actingAs($admin)->delete('/admin/modules/blog')->assertRedirect();

        $this->assertDatabaseMissing('modules', ['slug' => 'blog']);
    }
}
