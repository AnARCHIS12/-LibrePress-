<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Content;
use App\Models\User;
use App\Support\CmsPermission;

final class ContentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::CONTENT_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->can(CmsPermission::CONTENT_CREATE);
    }

    public function update(User $user, Content $content): bool
    {
        return $user->can(CmsPermission::CONTENT_UPDATE)
            && ($user->can(CmsPermission::CONTENT_PUBLISH) || $content->author_id === $user->id);
    }

    public function delete(User $user, Content $content): bool
    {
        return $user->can(CmsPermission::CONTENT_DELETE);
    }
}

