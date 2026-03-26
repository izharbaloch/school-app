<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
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
        'address',
        'admission_date',
        'student_class_id',
        'section_id',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'status' => 'boolean',
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
}
