<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Redirect;
use App\Models\User;
use App\Support\CmsPermission;

final class RedirectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::SETTINGS_MANAGE);
    }

    public function create(User $user): bool
    {
        return $user->can(CmsPermission::SETTINGS_MANAGE);
    }

    public function delete(User $user, Redirect $redirect): bool
    {
        return $user->can(CmsPermission::SETTINGS_MANAGE);
    }
}

