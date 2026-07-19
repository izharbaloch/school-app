<?php

namespace App\Policies;

use App\Models\Guardian;
use App\Models\User;

class GuardianPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('parents.view');
    }

    public function view(User $user, Guardian $guardian): bool
    {
        return Guardian::allowedForUser($user)->whereKey($guardian->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('parents.create');
    }

    public function update(User $user, Guardian $guardian): bool
    {
        return $this->view($user, $guardian) && $user->hasPermissionTo('parents.edit');
    }

    public function delete(User $user, Guardian $guardian): bool
    {
        return $this->view($user, $guardian) && $user->hasPermissionTo('parents.delete');
    }
}
