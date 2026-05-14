<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Media;
use App\Models\User;
use App\Support\CmsPermission;

final class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::MEDIA_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->can(CmsPermission::MEDIA_UPLOAD);
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->can(CmsPermission::MEDIA_DELETE) || $media->created_by === $user->id;
    }
}

