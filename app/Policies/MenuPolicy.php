<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use App\Support\CmsPermission;

final class MenuPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::SETTINGS_MANAGE);
    }

    public function create(User $user): bool
    {
        return $user->can(CmsPermission::SETTINGS_MANAGE);
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->can(CmsPermission::SETTINGS_MANAGE);
    }
}

