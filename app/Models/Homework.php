<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homework';

    protected $fillable = [
        'title', 'description', 'student_class_id', 'section_id',
        'subject_id', 'teacher_id', 'created_by', 'assigned_date',
        'due_date', 'attachment_path', 'status',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'due_date'      => 'date',
        'status'        => 'boolean',
    ];

    public function studentClass()   { return $this->belongsTo(StudentClass::class, 'student_class_id'); }
    public function section()        { return $this->belongsTo(Section::class); }
    public function subject()        { return $this->belongsTo(Subject::class); }
    public function teacher()        { return $this->belongsTo(Teacher::class); }
    public function creator()        { return $this->belongsTo(User::class, 'created_by'); }
    public function submissions()    { return $this->hasMany(HomeworkSubmission::class); }

    public function isOverdue(): bool
    {
        return $this->due_date < now() && $this->status;
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole(['super admin', 'admin', 'principal'])) {
            return $query;
        }
        if ($user->hasRole('teacher') && $user->teacher) {
            return $query->where('teacher_id', $user->teacher->id);
        }
        if ($user->hasRole('student') && $user->student) {
            return $query->where('student_class_id', $user->student->student_class_id);
        }
        return $query->whereRaw('1=0');
    }
}
