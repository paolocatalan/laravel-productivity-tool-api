<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Models\v1\Project;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function manageProject(User $user): bool
    {
        return $user->role === Role::ROLE_MANAGER;
    }

    public function deleteProject(User $user): bool
    {
        return $user->role === Role::ROLE_MANAGER;
    }
}
