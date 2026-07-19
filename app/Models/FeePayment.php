<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_fee_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_no',
        'remarks',
        'received_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal') || $user->hasRole('accountant')) {
            return $query;
        }

        return $query->whereHas('studentFee', function ($q) use ($user) {
            $q->allowedForUser($user);
        });
    }
}
