<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('attendance.view');
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return true;
        }

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            return $teacher && $attendance->student_class_id === $teacher->student_class_id
                && $attendance->section_id === $teacher->section_id;
        }

        if ($user->hasRole('parent') || $user->hasRole('student')) {
            return $attendance->attendanceStudents()
                ->whereHas('student', function ($q) use ($user) {
                    $q->allowedForUser($user);
                })->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('attendance.mark');
    }

    public function update(User $user, Attendance $attendance): bool
    {
        if (!$this->view($user, $attendance)) {
            return false;
        }
        return $user->hasPermissionTo('attendance.edit');
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        if (!$this->view($user, $attendance)) {
            return false;
        }
        return $user->hasRole('super admin') || $user->hasRole('admin');
    }
}
