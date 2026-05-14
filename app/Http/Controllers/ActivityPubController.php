<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ActivityPubActor;
use App\Models\ActivityPubInboxItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ActivityPubController
{
    public function webfinger(Request $request): JsonResponse
    {
        $resource = (string) $request->query('resource');
        $username = str($resource)->after('acct:')->before('@')->toString();
        $actor = ActivityPubActor::query()->where('username', $username)->where('enabled', true)->firstOrFail();

        return response()->json([
            'subject' => 'acct:'.$actor->username.'@'.$request->getHost(),
            'aliases' => [url("/@{$actor->username}")],
            'links' => [[
                'rel' => 'self',
                'type' => 'application/activity+json',
                'href' => url("/@{$actor->username}"),
            ]],
        ]);
    }

    public function actor(string $username): JsonResponse
    {
        $actor = ActivityPubActor::query()->where('username', $username)->where('enabled', true)->firstOrFail();

        return response()
            ->json([
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => $actor->type,
                'id' => url("/@{$actor->username}"),
                'preferredUsername' => $actor->username,
                'inbox' => url("/api/v1/activitypub/{$actor->username}/inbox"),
                'outbox' => url("/api/v1/activitypub/{$actor->username}/outbox"),
                'publicKey' => [
                    'id' => url("/@{$actor->username}#main-key"),
                    'owner' => url("/@{$actor->username}"),
                    'publicKeyPem' => $actor->public_key,
                ],
            ])
            ->header('Content-Type', 'application/activity+json');
    }

    public function inbox(Request $request, string $username): JsonResponse
    {
        $actor = ActivityPubActor::query()->where('username', $username)->where('enabled', true)->firstOrFail();
        $payload = $request->json()->all();

        ActivityPubInboxItem::query()->create([
            'actor_id' => $actor->id,
            'remote_actor' => (string) data_get($payload, 'actor'),
            'payload' => $payload,
            'status' => 'received',
        ]);

        return response()->json(['accepted' => true], 202);
    }

    public function outbox(string $username): JsonResponse
    {
        $actor = ActivityPubActor::query()->where('username', $username)->where('enabled', true)->firstOrFail();
        $items = $actor->outbox()->latest()->limit(20)->get();

        return response()
            ->json([
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'type' => 'OrderedCollection',
                'id' => url("/api/v1/activitypub/{$actor->username}/outbox"),
                'totalItems' => $actor->outbox()->count(),
                'orderedItems' => $items->pluck('payload')->all(),
            ])
            ->header('Content-Type', 'application/activity+json');
    }
}

