<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('media.upload');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $mimes = config('librepress.security.allowed_upload_mimes', []);
        $maxSize = (int) config('librepress.security.max_upload_size_mb', 32) * 1024;

        return [
            'file' => [
                'required',
                'file',
                'mimetypes:'.implode(',', $mimes),
                'max:'.$maxSize,
            ],
            'alt' => ['nullable', 'string', 'max:255', 'required_if_file_image:file'],
        ];
    }
}
