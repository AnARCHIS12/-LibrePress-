<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class SystemHealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_system_health_and_clear_cache(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
        Cache::put('demo-key', 'value', 60);

        $this->actingAs($admin)->get('/admin/system')->assertOk()->assertSee('database');
        $this->actingAs($admin)->post('/admin/system/cache/clear')->assertRedirect();

        $this->assertNull(Cache::get('demo-key'));
    }
}

