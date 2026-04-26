<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'employee_no',
        'name',
        'email',
        'phone',
        'cnic',
        'designation',
        'address',
        'student_class_id',
        'section_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return $query;
        }

        if ($user->hasRole('teacher')) {
            return $query->where('user_id', $user->id);
        }

        if ($user->hasRole('parent')) {
            return $query->whereHas('studentClass', function ($q) use ($user) {
                $q->whereHas('students', function ($sq) use ($user) {
                    $sq->allowedForUser($user);
                });
            });
        }

        if ($user->hasRole('student')) {
            $student = $user->student;
            if ($student) {
                return $query->where('student_class_id', $student->student_class_id)
                             ->where('section_id', $student->section_id);
            }
        }

        return $query->whereRaw('1 = 0');
    }
}
