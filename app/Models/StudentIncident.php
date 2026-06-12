<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentIncident extends Model
{
    const TYPE_WARNING    = 'warning';
    const TYPE_DETENTION  = 'detention';
    const TYPE_SUSPENSION = 'suspension';
    const TYPE_EXPULSION  = 'expulsion';
    const TYPE_MISCONDUCT = 'misconduct';
    const TYPE_OTHER      = 'other';

    const SEVERITY_MINOR    = 'minor';
    const SEVERITY_MODERATE = 'moderate';
    const SEVERITY_SEVERE   = 'severe';

    const STATUS_OPEN     = 'open';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED   = 'closed';

    protected $fillable = [
        'student_id', 'title', 'incident_type', 'severity',
        'incident_date', 'description', 'action_taken',
        'suspension_from', 'suspension_to',
        'status', 'resolution_notes', 'reported_by',
    ];

    protected $casts = [
        'incident_date'   => 'date',
        'suspension_from' => 'date',
        'suspension_to'   => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function getSeverityBadgeAttribute(): string
    {
        return match ($this->severity) {
            self::SEVERITY_SEVERE   => 'danger',
            self::SEVERITY_MODERATE => 'warning',
            default                 => 'secondary',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_RESOLVED => 'success',
            self::STATUS_CLOSED   => 'dark',
            default               => 'primary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->incident_type) {
            self::TYPE_WARNING    => 'Warning',
            self::TYPE_DETENTION  => 'Detention',
            self::TYPE_SUSPENSION => 'Suspension',
            self::TYPE_EXPULSION  => 'Expulsion',
            self::TYPE_MISCONDUCT => 'Misconduct',
            default               => 'Other',
        };
    }

    public function getSuspensionDaysAttribute(): ?int
    {
        if ($this->suspension_from && $this->suspension_to) {
            return $this->suspension_from->diffInDays($this->suspension_to) + 1;
        }
        return null;
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole(['super admin', 'admin', 'principal'])) {
            return $query;
        }

        if ($user->hasRole('teacher') && $user->teacher) {
            return $query->whereHas('student', fn($q) =>
                $q->where('student_class_id', $user->teacher->student_class_id)
                  ->where('section_id', $user->teacher->section_id)
            );
        }

        if ($user->hasRole('student') && $user->student) {
            return $query->where('student_id', $user->student->id);
        }

        if ($user->hasRole('parent') && $user->guardian) {
            return $query->whereHas('student', fn($q) =>
                $q->where('guardian_id', $user->guardian->id)
            );
        }

        return $query->whereRaw('1 = 0');
    }
}
