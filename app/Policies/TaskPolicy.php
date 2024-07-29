<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function createTask(User $user) : bool
    {
        return $user->role === Role::ROLE_MANAGER;
    }

    public function updateTask(User $user, Task $task): bool
    {
        return $user->id === $task->user_id || $user->role === Role::ROLE_MANAGER;
    }

    public function deleteTask(User $user) : bool
    {
        return $user->role === Role::ROLE_MANAGER;
    }
}
