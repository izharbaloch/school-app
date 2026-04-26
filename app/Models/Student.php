<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'guardian_id',
        'admission_no',
        'roll_no',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'father_name',
        'mother_name',
        'guardian_phone',
        'guardian_cnic_no',
        'address',
        'admission_date',
        'student_class_id',
        'section_id',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
    ];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function attachments()
    {
        return $this->hasMany(StudentAttachment::class);
    }

    public function profilePhoto()
    {
        return $this->hasOne(StudentAttachment::class)
            ->where('document_type', 'student_photo');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceStudent::class);
    }

    public function getFullNameAttribute()
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
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

        if ($user->hasRole('parent')) {
            $guardian = $user->guardian;
            if ($guardian) {
                return $query->where('guardian_id', $guardian->id);
            }
        }

        if ($user->hasRole('student')) {
            $student = $user->student;
            if ($student) {
                return $query->where('id', $student->id);
            }
        }

        return $query->whereRaw('1 = 0'); // Deny if no conditions matched
    }
}
