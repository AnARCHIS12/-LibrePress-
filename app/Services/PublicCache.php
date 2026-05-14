<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Content;
use Illuminate\Support\Facades\Cache;

final class PublicCache
{
    public function contentKey(string $slug): string
    {
        return "content.public.$slug";
    }

    public function forgetContent(Content $content): void
    {
        Cache::forget($this->contentKey($content->slug));
    }

    public function flush(): void
    {
        Cache::flush();
    }
}

