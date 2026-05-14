<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Models\Content;
use App\Models\Media;
use App\Http\Controllers\ActivityPubController;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('pages', fn () => response()->json([
        'data' => Content::query()->where('type', 'page')->where('status', 'published')->latest()->paginate(20),
    ]))->name('pages.index');

    Route::get('posts', fn () => response()->json([
        'data' => Content::query()->where('type', 'post')->where('status', 'published')->latest('published_at')->paginate(20),
    ]))->name('posts.index');

    Route::get('search', fn () => response()->json([
        'data' => Content::query()
            ->where('status', 'published')
            ->where('title', 'like', '%'.request('q').'%')
            ->limit(20)
            ->get(),
    ]))->name('search');

    Route::get('media', fn () => response()->json(['data' => Media::query()->latest()->paginate(20)]))->middleware('auth:sanctum')->name('media.index');
    Route::get('themes', fn () => response()->json(['data' => []]))->middleware('auth:sanctum')->name('themes.index');
    Route::get('modules', fn () => response()->json(['data' => []]))->middleware('auth:sanctum')->name('modules.index');
    Route::post('activitypub/{username}/inbox', [ActivityPubController::class, 'inbox'])->name('activity.inbox');
    Route::get('activitypub/{username}/outbox', [ActivityPubController::class, 'outbox'])->name('activity.outbox');
});
