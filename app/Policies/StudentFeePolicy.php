<?php

namespace App\Policies;

use App\Models\StudentFee;
use App\Models\User;

class StudentFeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('fees.view');
    }

    public function view(User $user, StudentFee $studentFee): bool
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal') || $user->hasRole('accountant')) {
            return true;
        }

        // Check if user is allowed to see the student this fee belongs to
        return $user->can('view', $studentFee->student);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('fees.create');
    }

    public function update(User $user, StudentFee $studentFee): bool
    {
        return $user->hasPermissionTo('fees.edit');
    }

    public function delete(User $user, StudentFee $studentFee): bool
    {
        return $user->hasPermissionTo('fees.delete');
    }
}
