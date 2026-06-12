<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMedicalRecord extends Model
{
    protected $fillable = [
        'student_id', 'blood_group',
        'height_cm', 'weight_kg',
        'vision_left', 'vision_right',
        'allergies', 'chronic_conditions', 'disabilities',
        'emergency_contact_name', 'emergency_contact_phone',
        'doctor_name', 'doctor_phone',
        'notes', 'updated_by',
    ];

    protected $casts = [
        'height_cm' => 'float',
        'weight_kg' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getBmiAttribute(): ?float
    {
        if ($this->height_cm && $this->weight_kg && $this->height_cm > 0) {
            $heightM = $this->height_cm / 100;
            return round($this->weight_kg / ($heightM * $heightM), 1);
        }
        return null;
    }
}
