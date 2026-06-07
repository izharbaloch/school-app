<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransportRoute extends Model
{
    use HasFactory;

    protected $table = 'transport_routes';

    protected $fillable = [
        'name',
        'vehicle_id',
        'start_point',
        'end_point',
        'monthly_fee',
        'stops',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'monthly_fee' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function studentTransports()
    {
        return $this->hasMany(StudentTransport::class, 'route_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, StudentTransport::class, 'route_id', 'id', 'id', 'student_id');
    }
}
