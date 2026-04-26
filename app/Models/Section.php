<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    public function classes()
    {
        return $this->belongsToMany(StudentClass::class, 'class_section');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function scopeAllowedForUser($query, $user)
    {
        if ($user->hasRole('super admin') || $user->hasRole('admin') || $user->hasRole('principal')) {
            return $query;
        }

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                return $query->where('id', $teacher->section_id);
            }
        }

        if ($user->hasRole('parent') || $user->hasRole('student')) {
            return $query->whereHas('attendances', function ($q) use ($user) {
                $q->allowedForUser($user);
            });
        }

        return $query->whereRaw('1 = 0');
    }
}
