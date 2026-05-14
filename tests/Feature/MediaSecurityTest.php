<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class MediaSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_upload_requires_alt_text(): void
    {
        $this->seed();
        Storage::fake('public');
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)->post('/admin/media', [
            'file' => $this->tinyPng(),
        ])->assertSessionHasErrors('alt');
    }

    public function test_valid_image_upload_is_stored(): void
    {
        $this->seed();
        Storage::fake('public');
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)->post('/admin/media', [
            'file' => $this->tinyPng(),
            'alt' => 'Photo descriptive',
        ])->assertRedirect();

        $this->assertDatabaseHas('media', ['alt' => 'Photo descriptive', 'mime_type' => 'image/png']);
    }

    private function tinyPng(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'photo.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=') ?: '',
        );
    }
}
