<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'student_class_id',
        'obtained_marks',
        'total_marks',
        'passing_marks',
        'academic_year',
        'remarks',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function getIsPassAttribute()
    {
        return $this->obtained_marks >= $this->passing_marks;
    }

    public static function gradeForPercentage(float|int $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default => 'F',
        };
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
