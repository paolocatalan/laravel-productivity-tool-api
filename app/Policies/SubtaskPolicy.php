<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Subtask;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubtaskPolicy
{
    public function viewSubtask(User $user, Subtask $subtask): bool
    {
        return $user->id === $subtask->user_id;
    }

    public function createSubtask(User $user): bool
    {
        return $user->role === Role::ROLE_MANAGER;
    }

    public function updateSubtask(User $user, Subtask $subtask): bool
    {
        return $user->id === $subtask->user_id || $user->role === Role::ROLE_MANAGER;
    }

    public function deleteSubtask(User $user): bool
    {
        return $user->role === Role::ROLE_MANAGER;
    }

}
