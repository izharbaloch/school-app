<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsActivity extends Model
{
    protected $fillable = [
        'name', 'category', 'description', 'coach_name',
        'venue', 'schedule', 'max_members', 'status',
    ];

    protected $casts = ['status' => 'boolean'];

    public function enrollments()
    {
        return $this->hasMany(StudentActivityEnrollment::class);
    }

    public function activeEnrollments()
    {
        return $this->hasMany(StudentActivityEnrollment::class)->where('status', true);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->max_members > 0
            && $this->activeEnrollments()->count() >= $this->max_members;
    }

    public function getCategoryBadgeAttribute(): string
    {
        return match ($this->category) {
            'sport' => 'primary',
            'club'  => 'info',
            'art'   => 'warning',
            default => 'secondary',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
