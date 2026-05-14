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

    public function test_non_image_remote_media_meta_can_be_stored(): void
    {
        \App\Models\Media::query()->create([
            'disk' => 'remote',
            'path' => 'https://example.test/file.pdf',
            'mime_type' => 'application/pdf',
            'size' => 0,
            'hash' => hash('sha256', 'https://example.test/file.pdf'),
            'meta' => ['source' => 'test'],
        ]);

        $this->assertDatabaseHas('media', ['path' => 'https://example.test/file.pdf']);
    }

    private function tinyPng(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'photo.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=') ?: '',
        );
    }
}
