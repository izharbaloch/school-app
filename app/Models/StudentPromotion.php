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
}
