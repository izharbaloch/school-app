<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
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
}
