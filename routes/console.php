<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ActivityPubActor;
use App\Models\Content;
use App\Jobs\DeliverActivityPubOutboxItem;
use App\Services\ActivityPubOutbox;
use App\Services\WordPress\WxrImporter;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('librepress:export', function (): int {
    $payload = [
        'exported_at' => now()->toISOString(),
        'contents' => \App\Models\Content::query()->get()->toArray(),
        'media' => \App\Models\Media::query()->get()->toArray(),
    ];

    Storage::disk('local')->put('exports/librepress-export.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $this->info(storage_path('app/private/exports/librepress-export.json'));

    return self::SUCCESS;
})->purpose('Export site content and media metadata');

Artisan::command('librepress:backup', function (): int {
    $target = storage_path('app/private/backups');
    File::ensureDirectoryExists($target);

    if (config('database.default') === 'sqlite') {
        File::copy(database_path('database.sqlite'), $target.'/database-'.now()->format('Ymd-His').'.sqlite');
    }

    $this->info($target);

    return self::SUCCESS;
})->purpose('Create a lightweight local backup');

Artisan::command('librepress:import-wordpress {file}', function (WxrImporter $importer): int {
    $file = (string) $this->argument('file');
    $admin = User::query()->where('is_admin', true)->first();

    if (! $admin) {
        $this->error('No admin user found.');

        return self::FAILURE;
    }

    if (! is_file($file)) {
        $this->error("File not found: $file");

        return self::FAILURE;
    }

    $summary = $importer->import($file, $admin);
    $this->info('Imported WordPress WXR: '.json_encode($summary, JSON_UNESCAPED_SLASHES));

    return self::SUCCESS;
})->purpose('Import pages and posts from a WordPress WXR export');

Artisan::command('librepress:activitypub-publish {contentId} {actor=admin}', function (ActivityPubOutbox $outbox): int {
    $content = Content::query()->findOrFail((int) $this->argument('contentId'));
    $actor = ActivityPubActor::query()->where('username', (string) $this->argument('actor'))->firstOrFail();
    $item = $outbox->publishContent($actor, $content);
    DeliverActivityPubOutboxItem::dispatch($item->id);
    $this->info("Queued ActivityPub outbox item {$item->id}.");

    return self::SUCCESS;
})->purpose('Create and queue an ActivityPub Create activity for a content item');
