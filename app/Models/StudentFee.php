<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFee extends Model
{
    use SoftDeletes;

    const UNPAID = 'unpaid';
    const PARTIAL = 'partial';
    const PAID = 'paid';

    protected $fillable = [
        'student_id',
        'fee_type_id',
        'month',
        'year',
        'amount',
        'discount',
        'fine',
        'paid_amount',
        'due_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'fine' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function getPayableAmountAttribute()
    {
        return ($this->amount + $this->fine) - $this->discount;
    }

    public function getRemainingAmountAttribute()
    {
        return $this->payable_amount - $this->paid_amount;
    }

    public function getMonthNameAttribute()
    {
        if (!$this->month) {
            return '-';
        }

        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getSlipNoAttribute()
    {
        return 'FEE-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal') || $user->hasRole('accountant')) {
            return $query;
        }

        return $query->whereHas('student', function ($q) use ($user) {
            $q->allowedForUser($user);
        });
    }
}
