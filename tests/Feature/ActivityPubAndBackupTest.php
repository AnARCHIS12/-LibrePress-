<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ActivityPubInboxItem;
use App\Models\ActivityPubOutboxItem;
use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ActivityPubAndBackupTest extends TestCase
{
    use RefreshDatabase;

    public function test_webfinger_actor_inbox_and_outbox_are_available(): void
    {
        $this->seed();

        $this->getJson('/.well-known/webfinger?resource=acct:admin@example.test')
            ->assertOk()
            ->assertJsonPath('links.0.type', 'application/activity+json');

        $this->getJson('/@admin')->assertOk()->assertJsonPath('preferredUsername', 'admin');

        $this->postJson('/api/v1/activitypub/admin/inbox', [
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'type' => 'Follow',
            'actor' => 'https://remote.example/@alice',
        ])->assertAccepted();

        $this->assertSame(1, ActivityPubInboxItem::query()->count());
        $this->getJson('/api/v1/activitypub/admin/outbox')->assertOk()->assertJsonPath('type', 'OrderedCollection');
    }

    public function test_admin_can_trigger_export_from_ui(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)->post('/admin/exports')->assertRedirect();

        $this->assertDatabaseHas('activity_log', ['description' => 'export.created']);
    }

    public function test_activitypub_publish_command_creates_outbox_item(): void
    {
        $this->seed();
        $content = Content::query()->where('slug', 'premier-article')->firstOrFail();

        $this->artisan('librepress:activitypub-publish', [
            'contentId' => $content->id,
            'actor' => 'admin',
        ])->assertSuccessful();

        $this->assertSame(1, ActivityPubOutboxItem::query()->count());
        $this->assertSame('delivered', ActivityPubOutboxItem::query()->first()?->status);
    }
}
