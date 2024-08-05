<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Role;
use App\Models\User;

class TaskPolicy
{
    public function manageTask(User $user) : bool
    {
        return $user->role === Role::ROLE_MANAGER;
    }

    public function deleteTask(User $user) : bool
    {
        return $user->role === Role::ROLE_MANAGER;
    }
}
