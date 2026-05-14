<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;

final class PublishScheduledContent extends Command
{
    protected $signature = 'librepress:publish-scheduled';

    protected $description = 'Publish scheduled content whose publication date has arrived.';

    public function handle(): int
    {
        $count = Content::query()
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->update([
                'status' => 'published',
                'published_at' => now(),
                'scheduled_at' => null,
            ]);

        $this->info("Published {$count} scheduled content item(s).");

        return self::SUCCESS;
    }
}

