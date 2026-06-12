<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'applicant_type', 'user_id', 'student_id', 'leave_type_id',
        'from_date', 'to_date', 'total_days', 'reason', 'status',
        'rejection_reason', 'reviewed_by', 'reviewed_at', 'created_by',
    ];

    protected $casts = [
        'from_date'   => 'date',
        'to_date'     => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getApplicantNameAttribute(): string
    {
        if ($this->applicant_type === 'student') {
            return $this->student?->full_name ?? '—';
        }
        return $this->user?->name ?? '—';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default               => 'warning',
        };
    }

    public static function workingDays(\Carbon\Carbon $from, \Carbon\Carbon $to): int
    {
        $days = 0;
        $current = $from->copy();
        while ($current->lte($to)) {
            if (!$current->isWeekend()) {
                $days++;
            }
            $current->addDay();
        }
        return max(1, $days);
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole(['super admin', 'admin', 'principal'])) {
            return $query;
        }

        if ($user->hasRole('teacher')) {
            return $query->where('user_id', $user->id);
        }

        if ($user->hasRole('student') && $user->student) {
            return $query->where('student_id', $user->student->id);
        }

        return $query->whereRaw('1 = 0');
    }
}
