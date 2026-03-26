<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    public function classes()
    {
        return $this->belongsToMany(StudentClass::class, 'class_subject');
    }
}
