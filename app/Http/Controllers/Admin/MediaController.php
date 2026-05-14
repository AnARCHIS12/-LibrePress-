<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MediaUploadRequest;
use App\Models\Media;
use App\Services\AuditLogger;
use App\Services\MalwareScanner;
use App\Services\MediaDerivativeGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final readonly class MediaController
{
    public function __construct(
        private MalwareScanner $scanner,
        private MediaDerivativeGenerator $derivatives,
        private AuditLogger $audit,
    ) {
    }

    public function index(): View
    {
        Gate::authorize('viewAny', Media::class);

        return view('admin.media.index', [
            'media' => Media::query()->latest()->paginate(24),
        ]);
    }

    public function store(MediaUploadRequest $request): RedirectResponse
    {
        Gate::authorize('create', Media::class);
        $validated = $request->validated();
        $file = $validated['file'];
        $this->scanner->assertClean($file);
        [$width, $height] = $this->imageDimensions($file->getRealPath(), (string) $file->getMimeType());

        abort_if($width > (int) config('librepress.security.max_image_width', 6000), 422, 'Image trop large.');
        abort_if($height > (int) config('librepress.security.max_image_height', 6000), 422, 'Image trop haute.');

        $path = $file->store('media', 'public');

        $media = Media::query()->create([
            'disk' => 'public',
            'path' => $path,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
            'width' => $width ?: null,
            'height' => $height ?: null,
            'alt' => $validated['alt'] ?? null,
            'hash' => hash_file('sha256', $file->getRealPath()),
            'created_by' => $request->user()?->id,
        ]);
        $this->derivatives->generate($media);
        $this->audit->log($request, 'media.uploaded', $media, ['mime_type' => $media->mime_type]);

        return back()->with('status', 'Media ajoute.');
    }

    public function destroy(Request $request, Media $medium): RedirectResponse
    {
        Gate::authorize('delete', $medium);
        $this->audit->log($request, 'media.deleted', $medium);
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return back()->with('status', 'Media supprime.');
    }

    /**
     * @return array{0:int,1:int}
     */
    private function imageDimensions(string $path, string $mimeType): array
    {
        if (! str_starts_with($mimeType, 'image/')) {
            return [0, 0];
        }

        $size = getimagesize($path);

        return is_array($size) ? [(int) $size[0], (int) $size[1]] : [0, 0];
    }
}
