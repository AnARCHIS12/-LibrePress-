<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchController
{
    public function __invoke(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        $results = $query === ''
            ? collect()
            : Content::query()
                ->where('status', 'published')
                ->where(function ($builder) use ($query): void {
                    $builder
                        ->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('body_html', 'like', "%{$query}%");
                })
                ->latest('published_at')
                ->limit(25)
                ->get();

        return view('front.search', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}

