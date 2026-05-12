<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use Illuminate\View\View;

final class DashboardController
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'pages' => Content::query()->where('type', 'page')->count(),
            'posts' => Content::query()->where('type', 'post')->count(),
            'drafts' => Content::query()->where('status', 'draft')->count(),
        ]);
    }
}

