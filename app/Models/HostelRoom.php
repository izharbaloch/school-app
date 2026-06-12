<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelRoom extends Model
{
    protected $fillable = [
        'hostel_id', 'room_number', 'floor', 'capacity', 'room_type', 'status',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class);
    }

    public function activeAllocations()
    {
        return $this->hasMany(HostelAllocation::class)->where('status', 'active');
    }

    public function getCurrentOccupancyAttribute(): int
    {
        return $this->activeAllocations()->count();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->capacity - $this->current_occupancy);
    }

    public function getIsFull(): bool
    {
        return $this->available_slots === 0;
    }

    public function updateOccupancyStatus(): void
    {
        $occ = $this->current_occupancy;
        if ($this->status !== 'maintenance') {
            $this->update(['status' => $occ >= $this->capacity ? 'occupied' : 'available']);
        }
    }
}
