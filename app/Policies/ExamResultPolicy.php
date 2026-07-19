<?php

namespace App\Policies;

use App\Models\ExamResult;
use App\Models\User;

class ExamResultPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('marks.view');
    }

    public function view(User $user, ExamResult $examResult): bool
    {
        return ExamResult::allowedForUser($user)->whereKey($examResult->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('marks.create');
    }

    public function update(User $user, ExamResult $examResult): bool
    {
        return $this->view($user, $examResult) && $user->hasPermissionTo('marks.edit');
    }
}
