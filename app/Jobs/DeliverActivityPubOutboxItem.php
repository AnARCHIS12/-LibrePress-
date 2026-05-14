<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\ActivityPubFollower;
use App\Models\ActivityPubOutboxItem;
use App\Services\ActivityPubSigner;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

final class DeliverActivityPubOutboxItem implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $outboxItemId)
    {
    }

    public function handle(ActivityPubSigner $signer): void
    {
        $item = ActivityPubOutboxItem::query()->findOrFail($this->outboxItemId);
        $actor = $item->actor()->firstOrFail();
        $body = json_encode($item->payload, JSON_UNESCAPED_SLASHES) ?: '{}';

        $followers = ActivityPubFollower::query()
            ->where('actor_id', $actor->id)
            ->where('status', 'accepted')
            ->get();

        foreach ($followers as $follower) {
            $headers = $signer->sign($actor, 'POST', $follower->inbox_url, $body, [
                'Content-Type' => 'application/activity+json',
            ]);

            Http::withHeaders($headers)->timeout(10)->post($follower->inbox_url, $item->payload);
        }

        $item->update(['status' => 'delivered', 'delivered_at' => now()]);
    }
}

