<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityPubActor;
use App\Models\ActivityPubOutboxItem;
use App\Models\Content;

final class ActivityPubOutbox
{
    public function publishContent(ActivityPubActor $actor, Content $content): ActivityPubOutboxItem
    {
        return ActivityPubOutboxItem::query()->create([
            'actor_id' => $actor->id,
            'status' => 'pending',
            'payload' => [
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'Create',
                'actor' => url("/@{$actor->username}"),
                'object' => [
                    'id' => route('front.show', $content->slug),
                    'type' => 'Article',
                    'name' => $content->title,
                    'summary' => $content->excerpt,
                    'url' => route('front.show', $content->slug),
                    'published' => optional($content->published_at)->toAtomString(),
                ],
            ],
        ]);
    }
}

