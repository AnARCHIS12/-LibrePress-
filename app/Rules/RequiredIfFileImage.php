<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final readonly class RequiredIfFileImage implements ValidationRule
{
    public function __construct(private string $fileField)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $file = request()->file($this->fileField);

        if (! $file instanceof UploadedFile) {
            return;
        }

        if (str_starts_with((string) $file->getMimeType(), 'image/') && blank($value)) {
            $fail('Le texte alternatif est obligatoire pour les images.');
        }
    }
}

