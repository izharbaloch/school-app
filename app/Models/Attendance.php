<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'attendance_date',
        'student_class_id',
        'section_id',
        'taken_by',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function takenBy()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }

    public function attendanceStudents()
    {
        return $this->hasMany(AttendanceStudent::class);
    }

    public function presentCount()
    {
        return $this->attendanceStudents()->where('status', AttendanceStudent::PRESENT)->count();
    }

    public function absentCount()
    {
        return $this->attendanceStudents()->where('status', AttendanceStudent::ABSENT)->count();
    }

    public function leaveCount()
    {
        return $this->attendanceStudents()->where('status', AttendanceStudent::LEAVE)->count();
    }

    public function lateCount()
    {
        return $this->attendanceStudents()->where('status', AttendanceStudent::LATE)->count();
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return $query;
        }

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                return $query->where('student_class_id', $teacher->student_class_id)
                             ->where('section_id', $teacher->section_id);
            }
        }

        if ($user->hasRole('parent') || $user->hasRole('student')) {
            return $query->whereHas('attendanceStudents', function ($q) use ($user) {
                $q->allowedForUser($user);
            });
        }

        return $query->whereRaw('1 = 0');
    }
}
