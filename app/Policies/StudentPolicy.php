<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('students.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return true;
        }

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            return $teacher && $student->student_class_id === $teacher->student_class_id
                && $student->section_id === $teacher->section_id;
        }

        if ($user->hasRole('parent')) {
            $guardian = $user->guardian;
            return $guardian && $student->guardian_id === $guardian->id;
        }

        if ($user->hasRole('student')) {
            $userStudent = $user->student;
            return $userStudent && $student->id === $userStudent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('students.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Student $student): bool
    {
        // First check if they even have access to this student's data
        if (!$this->view($user, $student)) {
            return false;
        }
        return $user->hasPermissionTo('students.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        if (!$this->view($user, $student)) {
            return false;
        }
        return $user->hasPermissionTo('students.delete');
    }
}
