<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Support\CmsPermission;

final class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::USERS_MANAGE);
    }

    public function update(User $user, User $model): bool
    {
        return $user->can(CmsPermission::USERS_MANAGE) && $user->id !== $model->id;
    }
}

