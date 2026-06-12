<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentVaccination extends Model
{
    protected $fillable = [
        'student_id', 'vaccine_name', 'date_administered',
        'next_due_date', 'administered_by', 'notes',
    ];

    protected $casts = [
        'date_administered' => 'date',
        'next_due_date'     => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getIsDueAttribute(): bool
    {
        return $this->next_due_date && $this->next_due_date->isPast();
    }
}
