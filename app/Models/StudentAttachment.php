<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttachment extends Model
{
    protected $fillable = [
        'student_id',
        'document_type',
        'title',
        'file_path',
        'file_name',
        'file_extension',
        'file_size',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
