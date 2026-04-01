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
}
