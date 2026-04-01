<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceStudent extends Model
{
    const PRESENT = 'present';
    const ABSENT  = 'absent';
    const LEAVE   = 'leave';
    const LATE    = 'late';

    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
        'remarks',
    ];

    public static function statuses(): array
    {
        return [
            self::PRESENT => 'Present',
            self::ABSENT  => 'Absent',
            self::LEAVE   => 'Leave',
            self::LATE    => 'Late',
        ];
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
