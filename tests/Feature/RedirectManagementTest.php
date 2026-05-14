<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RedirectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_redirect_and_public_request_redirects(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)->post('/admin/redirects', [
            'source_path' => '/ancienne-page',
            'target_path' => '/premier-article',
            'status_code' => 301,
        ])->assertRedirect();

        $this->get('/ancienne-page')->assertRedirect('/premier-article');
    }
}

