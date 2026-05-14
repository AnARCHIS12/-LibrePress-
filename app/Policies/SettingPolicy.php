<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use App\Support\CmsPermission;

final class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::SETTINGS_MANAGE);
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->can(CmsPermission::SETTINGS_MANAGE);
    }
}

