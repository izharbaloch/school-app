<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentActivityEnrollment extends Model
{
    protected $fillable = [
        'student_id', 'sports_activity_id', 'role', 'joined_date', 'notes', 'status',
    ];

    protected $casts = [
        'joined_date' => 'date',
        'status'      => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function activity()
    {
        return $this->belongsTo(SportsActivity::class, 'sports_activity_id');
    }
}
