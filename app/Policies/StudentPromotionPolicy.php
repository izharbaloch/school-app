<?php

namespace App\Policies;

use App\Models\StudentPromotion;
use App\Models\User;

class StudentPromotionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('students.view');
    }

    public function view(User $user, StudentPromotion $studentPromotion): bool
    {
        return StudentPromotion::allowedForUser($user)->whereKey($studentPromotion->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('students.edit');
    }
}
