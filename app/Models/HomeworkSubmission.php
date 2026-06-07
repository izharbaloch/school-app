<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeworkSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'homework_id', 'student_id', 'remarks', 'attachment_path',
        'submitted_date', 'status', 'marks', 'teacher_remarks',
    ];

    protected $casts = [
        'submitted_date' => 'date',
    ];

    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVIEWED  = 'reviewed';
    public const STATUS_LATE      = 'late';
    public const STATUS_MISSING   = 'missing';

    public function homework() { return $this->belongsTo(Homework::class); }
    public function student()  { return $this->belongsTo(Student::class); }
}
