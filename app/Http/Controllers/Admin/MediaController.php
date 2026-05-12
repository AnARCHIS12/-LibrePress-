<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class MediaController
{
    public function index(): View
    {
        return view('admin.media.index', [
            'media' => Media::query()->latest()->paginate(24),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:32768'],
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $validated['file'];
        $path = $file->store('media', 'public');

        Media::query()->create([
            'disk' => 'public',
            'path' => $path,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
            'alt' => $validated['alt'] ?? null,
            'hash' => hash_file('sha256', $file->getRealPath()),
            'created_by' => $request->user()?->id,
        ]);

        return back()->with('status', 'Media ajoute.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return back()->with('status', 'Media supprime.');
    }
}

