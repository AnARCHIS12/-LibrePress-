<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Support\CmsPermission;

final class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::COMMENTS_MODERATE);
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->can(CmsPermission::COMMENTS_MODERATE);
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->can(CmsPermission::COMMENTS_MODERATE);
    }
}

