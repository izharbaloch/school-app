<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'basic_salary', 'allowances', 'deductions',
        'month', 'year', 'payment_date', 'payment_method',
        'reference_no', 'status', 'paid_by', 'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowances'   => 'decimal:2',
        'deductions'   => 'decimal:2',
    ];

    public static array $months = [
        1=>'January', 2=>'February', 3=>'March', 4=>'April',
        5=>'May', 6=>'June', 7=>'July', 8=>'August',
        9=>'September', 10=>'October', 11=>'November', 12=>'December',
    ];

    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function paidBy()  { return $this->belongsTo(User::class, 'paid_by'); }

    public function getNetSalaryAttribute(): float
    {
        return (float)$this->basic_salary + (float)$this->allowances - (float)$this->deductions;
    }
}
