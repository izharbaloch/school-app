<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'category_id', 'amount', 'expense_date',
        'payment_method', 'reference_no', 'receipt_path', 'created_by', 'status',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
        'status'       => 'boolean',
    ];

    public function category()  { return $this->belongsTo(ExpenseCategory::class, 'category_id'); }
    public function creator()   { return $this->belongsTo(User::class, 'created_by'); }
}
