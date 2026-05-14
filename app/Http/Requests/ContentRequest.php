<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

final class ContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        return $this->isMethod('post')
            ? $user->can('content.create')
            : $user->can('content.update');
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:page,post'],
            'status' => ['required', 'in:draft,review,scheduled,published,archived'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'locale' => ['required', 'string', 'max:12'],
            'scheduled_at' => ['nullable', 'date'],
            'body_markdown' => ['nullable', 'string'],
            'body_blocks_json' => ['nullable', 'json'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'noindex' => ['nullable', 'boolean'],
            'terms' => ['array'],
            'terms.*' => ['integer', 'exists:terms,id'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toContentData(): array
    {
        $validated = $this->validated();
        $markdown = $validated['body_markdown'] ?? '';
        $document = app(\App\Services\BlockDocument::class)->fromJson($validated['body_blocks_json'] ?? null, $markdown);

        return [
            'type' => $validated['type'],
            'status' => $validated['status'],
            'author_id' => $this->user()?->id,
            'slug' => $validated['slug'] ?: Str::slug($validated['title']),
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'locale' => $validated['locale'],
            'body_json' => $document,
            'scheduled_at' => $validated['status'] === 'scheduled' ? $validated['scheduled_at'] ?? now()->addHour() : null,
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'meta' => [
                'editor' => 'markdown-block-v1',
                'seo' => [
                    'title' => $validated['meta_title'] ?? null,
                    'description' => $validated['meta_description'] ?? null,
                    'canonical_url' => $validated['canonical_url'] ?? null,
                    'noindex' => $this->boolean('noindex'),
                ],
            ],
        ];
    }
}
