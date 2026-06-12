<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $fillable = [
        'name', 'type', 'warden_name', 'warden_phone',
        'address', 'description', 'status',
    ];

    protected $casts = ['status' => 'boolean'];

    public function rooms()
    {
        return $this->hasMany(HostelRoom::class);
    }

    public function getOccupancyAttribute(): string
    {
        $capacity = $this->rooms->sum('capacity');
        $occupied = $this->rooms->sum(fn($r) => $r->active_allocations_count ?? 0);
        return "{$occupied}/{$capacity}";
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
