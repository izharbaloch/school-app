<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'student_class_id',
        'fee_type_id',
        'amount',
        'status',
    ];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}
