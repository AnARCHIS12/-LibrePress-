<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Taxonomy;
use App\Models\User;
use App\Support\CmsPermission;

final class TaxonomyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(CmsPermission::CONTENT_UPDATE);
    }

    public function create(User $user): bool
    {
        return $user->can(CmsPermission::CONTENT_UPDATE);
    }

    public function update(User $user, Taxonomy $taxonomy): bool
    {
        return $user->can(CmsPermission::CONTENT_UPDATE);
    }

    public function delete(User $user, Taxonomy $taxonomy): bool
    {
        return $user->can(CmsPermission::CONTENT_DELETE);
    }
}

