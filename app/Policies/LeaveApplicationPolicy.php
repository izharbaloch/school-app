<?php

namespace App\Policies;

use App\Models\LeaveApplication;
use App\Models\User;

class LeaveApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('leaves.view');
    }

    public function view(User $user, LeaveApplication $leaveApplication): bool
    {
        return LeaveApplication::allowedForUser($user)->whereKey($leaveApplication->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('leaves.apply');
    }

    public function update(User $user, LeaveApplication $leaveApplication): bool
    {
        return $leaveApplication->status === LeaveApplication::STATUS_PENDING
            && ($leaveApplication->created_by === $user->id || $this->approve($user, $leaveApplication));
    }

    public function delete(User $user, LeaveApplication $leaveApplication): bool
    {
        return $leaveApplication->status === LeaveApplication::STATUS_PENDING
            && $leaveApplication->created_by === $user->id;
    }

    public function approve(User $user, LeaveApplication $leaveApplication): bool
    {
        return $user->hasPermissionTo('leaves.approve');
    }
}
