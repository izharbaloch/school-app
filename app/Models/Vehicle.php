<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'registration_no',
        'type',
        'capacity',
        'driver_name',
        'driver_phone',
        'driver_cnic',
        'status',
    ];

    protected $casts = ['status' => 'boolean'];

    public function routes()
    {
        return $this->hasMany(TransportRoute::class, 'vehicle_id');
    }
}
