<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final readonly class ContentTranslationController
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function store(Request $request, Content $content): RedirectResponse
    {
        Gate::authorize('create', Content::class);

        $validated = $request->validate([
            'locale' => ['required', 'string', 'max:12', 'different:source_locale'],
        ]);

        $group = $content->translation_group_id ?: (string) Str::uuid();
        $content->forceFill(['translation_group_id' => $group])->save();

        $copy = $content->replicate(['slug', 'locale', 'status', 'published_at', 'scheduled_at']);
        $copy->locale = $validated['locale'];
        $copy->slug = $content->slug.'-'.$validated['locale'];
        $copy->status = 'draft';
        $copy->published_at = null;
        $copy->scheduled_at = null;
        $copy->translation_group_id = $group;
        $copy->title = $content->title.' ['.$validated['locale'].']';
        $copy->save();
        $copy->terms()->sync($content->terms()->pluck('terms.id')->all());

        $this->audit->log($request, 'content.translation_created', $copy, [
            'source_id' => $content->id,
            'locale' => $validated['locale'],
        ]);

        return redirect()->route('admin.pages.edit', $copy)->with('status', 'Traduction creee.');
    }
}

