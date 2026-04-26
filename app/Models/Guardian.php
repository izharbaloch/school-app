<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'father_name', 'mother_name', 'guardian_phone', 'guardian_cnic_no', 'email', 'address', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return $query;
        }

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                return $query->whereHas('students', function ($q) use ($teacher) {
                    $q->where('student_class_id', $teacher->student_class_id)
                      ->where('section_id', $teacher->section_id);
                });
            }
        }

        if ($user->hasRole('parent')) {
            return $query->where('user_id', $user->id);
        }

        if ($user->hasRole('student')) {
            $student = $user->student;
            if ($student) {
                return $query->where('id', $student->guardian_id);
            }
        }

        return $query->whereRaw('1 = 0');
    }
}
