<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use App\Support\CmsPermission;

final class ModulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::MODULES_MANAGE);
    }

    public function update(User $user, Module $module): bool
    {
        return $user->can(CmsPermission::MODULES_MANAGE);
    }
}

