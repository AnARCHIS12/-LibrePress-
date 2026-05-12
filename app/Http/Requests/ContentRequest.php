<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

final class ContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
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
            'body_markdown' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'noindex' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toContentData(): array
    {
        $validated = $this->validated();
        $markdown = $validated['body_markdown'] ?? '';

        return [
            'type' => $validated['type'],
            'status' => $validated['status'],
            'author_id' => $this->user()?->id,
            'slug' => $validated['slug'] ?: Str::slug($validated['title']),
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'locale' => $validated['locale'],
            'body_json' => [
                'version' => 1,
                'blocks' => [
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'core/markdown',
                        'props' => ['text' => $markdown],
                    ],
                ],
            ],
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
