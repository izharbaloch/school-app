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

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_class_id');
    }
}
