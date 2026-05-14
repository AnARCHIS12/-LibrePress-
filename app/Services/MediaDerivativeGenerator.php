<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

final class MediaDerivativeGenerator
{
    public function generate(Media $media): void
    {
        if (! str_starts_with($media->mime_type, 'image/') || $media->disk !== 'public') {
            return;
        }

        $source = Storage::disk($media->disk)->path($media->path);

        if (! is_file($source)) {
            return;
        }

        $derivatives = [];

        foreach ([320, 768, 1280] as $width) {
            $target = $this->resize($source, $media->path, $width);

            if ($target) {
                $derivatives[(string) $width] = $target;
            }
        }

        $media->update([
            'meta' => [
                ...(array) $media->meta,
                'derivatives' => $derivatives,
            ],
        ]);
    }

    private function resize(string $source, string $path, int $targetWidth): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $info = getimagesize($source);

        if (! is_array($info) || $info[0] <= $targetWidth) {
            return null;
        }

        [$width, $height] = $info;
        $targetHeight = (int) round($height * ($targetWidth / $width));
        $src = match ($info['mime']) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png' => imagecreatefrompng($source),
            'image/webp' => imagecreatefromwebp($source),
            default => false,
        };

        if (! $src) {
            return null;
        }

        $dst = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
        $targetPath = preg_replace('/(\.[a-z0-9]+)$/i', "-{$targetWidth}.webp", $path) ?: $path."-{$targetWidth}.webp";
        $absoluteTarget = Storage::disk('public')->path($targetPath);
        @mkdir(dirname($absoluteTarget), 0775, true);
        imagewebp($dst, $absoluteTarget, 82);
        imagedestroy($src);
        imagedestroy($dst);

        return $targetPath;
    }
}

