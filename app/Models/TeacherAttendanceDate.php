<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendanceDate extends Model
{
    protected $fillable = [
        'attendance_date',
        'taken_by',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function takenBy()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }

    public function attendanceTeachers()
    {
        return $this->hasMany(AttendanceTeacher::class, 'attendance_date_id');
    }

    public function presentCount()
    {
        return $this->attendanceTeachers()->where('status', AttendanceTeacher::PRESENT)->count();
    }

    public function absentCount()
    {
        return $this->attendanceTeachers()->where('status', AttendanceTeacher::ABSENT)->count();
    }

    public function leaveCount()
    {
        return $this->attendanceTeachers()->where('status', AttendanceTeacher::LEAVE)->count();
    }

    public function lateCount()
    {
        return $this->attendanceTeachers()->where('status', AttendanceTeacher::LATE)->count();
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return $query;
        }

        if ($user->hasRole('teacher')) {
            return $query->whereHas('attendanceTeachers', function ($q) use ($user) {
                $q->allowedForUser($user);
            });
        }

        return $query->whereRaw('1 = 0');
    }
}
