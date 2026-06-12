<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name', 'max_days_per_year', 'applicable_to', 'is_paid', 'description', 'status',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'status'  => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeForApplicant($query, string $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->where('applicable_to', $type)->orWhere('applicable_to', 'both');
        });
    }
}
