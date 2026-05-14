<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Theme;
use App\Models\User;
use App\Support\CmsPermission;

final class ThemePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::THEMES_MANAGE);
    }

    public function update(User $user, Theme $theme): bool
    {
        return $user->can(CmsPermission::THEMES_MANAGE);
    }
}

