<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    const STATUS_PENDING     = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_ACCEPTED    = 'accepted';
    const STATUS_REJECTED    = 'rejected';
    const STATUS_ENROLLED    = 'enrolled';

    protected $fillable = [
        'application_no',
        'first_name', 'last_name', 'gender', 'date_of_birth',
        'father_name', 'mother_name', 'guardian_phone', 'guardian_email',
        'guardian_cnic_no', 'address',
        'applied_class_id', 'applied_section_id', 'academic_year',
        'previous_school', 'remarks',
        'status', 'rejection_reason',
        'reviewed_by', 'reviewed_at', 'enrolled_student_id', 'created_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'reviewed_at'   => 'datetime',
    ];

    public function appliedClass()
    {
        return $this->belongsTo(StudentClass::class, 'applied_class_id');
    }

    public function appliedSection()
    {
        return $this->belongsTo(Section::class, 'applied_section_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function enrolledStudent()
    {
        return $this->belongsTo(Student::class, 'enrolled_student_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->last_name ?? ''));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING      => 'Pending',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_ACCEPTED     => 'Accepted',
            self::STATUS_REJECTED     => 'Rejected',
            self::STATUS_ENROLLED     => 'Enrolled',
            default                   => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING      => 'secondary',
            self::STATUS_UNDER_REVIEW => 'info',
            self::STATUS_ACCEPTED     => 'success',
            self::STATUS_REJECTED     => 'danger',
            self::STATUS_ENROLLED     => 'primary',
            default                   => 'secondary',
        };
    }

    public static function generateApplicationNo(): string
    {
        $year = date('Y');
        $last = static::where('application_no', 'like', 'APP-%-' . $year)->orderByDesc('id')->first();
        $next = 1;
        if ($last && preg_match('/APP-(\d+)-' . $year . '/', $last->application_no, $m)) {
            $next = (int) $m[1] + 1;
        }
        return 'APP-' . str_pad($next, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole(['super admin', 'admin', 'principal', 'receptionist'])) {
            return $query;
        }
        return $query->whereRaw('1 = 0');
    }
}
