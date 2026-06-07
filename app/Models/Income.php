<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'source', 'amount', 'income_date',
        'payment_method', 'reference_no', 'created_by', 'status',
    ];

    protected $casts = [
        'income_date' => 'date',
        'amount'      => 'decimal:2',
        'status'      => 'boolean',
    ];

    public static array $sources = [
        'fee'      => 'Fee Collection',
        'donation' => 'Donation',
        'grant'    => 'Government Grant',
        'other'    => 'Other',
    ];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
