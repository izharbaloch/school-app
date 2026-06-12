<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $fillable = [
        'exam_id',
        'student_class_id',
        'section_id',
        'subject_id',
        'date',
        'start_time',
        'end_time',
        'room',
        'remarks',
        'status',
    ];

    protected $casts = [
        'date'   => 'date',
        'status' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function scopeForExamAndClass($query, $examId, $classId, $sectionId = null)
    {
        $query->where('exam_id', $examId)->where('student_class_id', $classId);

        if ($sectionId) {
            $query->where(function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId)->orWhereNull('section_id');
            });
        }

        return $query;
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole(['super admin', 'admin', 'principal'])) {
            return $query;
        }

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                return $query->where('student_class_id', $teacher->student_class_id)
                             ->where(function ($q) use ($teacher) {
                                 $q->where('section_id', $teacher->section_id)
                                   ->orWhereNull('section_id');
                             });
            }
        }

        return $query->where('status', true);
    }
}
