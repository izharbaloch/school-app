<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
    protected $fillable = [
        'student_id',
        'from_class_id',
        'to_class_id',
        'from_section_id',
        'to_section_id',
        'exam_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return $query;
        }

        return $query->whereHas('student', function ($q) use ($user) {
            $q->allowedForUser($user);
        });
    }
}
