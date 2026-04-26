<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $fillable = [
        'name',
        'numeric_name',
        'fee',
        'status',
    ];

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'class_section');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'student_class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_class_id');
    }

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class, 'student_class_id');
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return $query;
        }

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                return $query->where('id', $teacher->student_class_id);
            }
        }

        if ($user->hasRole('parent') || $user->hasRole('student')) {
            return $query->whereHas('students', function ($q) use ($user) {
                $q->allowedForUser($user);
            });
        }

        return $query->whereRaw('1 = 0');
    }
}
